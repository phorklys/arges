<?php
error_reporting(E_ALL);
require_once "../server/config.php";
require_once "db_update_befehle.php";

$dbVerbindung = connectDatabase($config_db_server, $config_db_user, $config_db_password, $config_db_database);

main($dbVerbindung);

function main(mysqli $dbVerbindung)
{
    $aktVersion = getVersion($dbVerbindung);
    if (isset($_GET['init']) && $_GET['init'] == 1) {
        $aktVersion = -1;
    }
    $hoechsteVersion = getHoechsteUpdateversion(getUpdateArray());
    echo "Update von Version " . $aktVersion . " auf Version " . $hoechsteVersion . ". ";
    if ($aktVersion < $hoechsteVersion) {
        updateDb($dbVerbindung, $aktVersion);
        echo "Update wird durchgeführt. ";
    } else {
        echo "Kein Update notwendig. ";
    }
    if (isset($_GET['test']) && $_GET['test'] == 1) {
        testdatenEinfuegen($dbVerbindung);
        echo "Testdaten wurden eingespielt. ";
    }
}

function connectDatabase(string $host, string $user, string $password, string $database): mysqli
{
    return new mysqli($host, $user, $password, $database);
}

function getVersion(mysqli $dbConnection): int
{
    $result = $dbConnection->query("SELECT wert_i FROM " . _t("db_config") . " WHERE parameter = 'version'");
    if ($result == false) {
        //Tabelle oder Zeile nicht vorhanden
        return -1;
    } else {
        //Zeile vorhanden, muss aber noch ausgelesen werden
        $value = $result->fetch_assoc();
        if ($value == null || $value['wert_i'] == null) {
            return -1;
        }
        return $value['wert_i'];
    }
}

/**
 * Fügt die Testdaten in die Datenbank ein
 */
function testdatenEinfuegen(mysqli $dbConnection)
{
    foreach (getTestdatenArray() as $key => $val) {
        $handle = fopen($val, 'r');
        $csv = fgetcsv($handle);
        $handle = fopen($val, 'r');
        $spaltennamen = array();
        $daten = array();
        while (($data = fgetcsv($handle)) !== false) {
            if (count($spaltennamen) == 0) {
                //es handelt sich um die Kopfzeile, die gesondert wegschreiben
                $spaltennamen = $data;
            } else {
                $daten[count($daten)] = $data;
            }
        }
        fclose($handle);

        //Prepared-Statement erzeugen
        $sql = "INSERT INTO " . _t($key) . " (";
        $bindTyp = "";
        foreach ($spaltennamen as $i => $spalte) {
            if ($i > 0) {
                $sql .= ", ";
            }
            $sql .= $spalte;
        }
        $sql .= ") VALUES (";
        foreach ($spaltennamen as $i => $spalte) {
            if ($i > 0) {
                $sql .= ", ";
            }
            $sql .= "?";
            $bindTyp .= "s";
        }
        $sql .= ")";
        $stmt = $dbConnection->prepare($sql);
        //pro Datensatztabelle füllen
        foreach ($daten as $i => $datum) {
            $stmt->bind_param($bindTyp, ...$datum);
            $stmt->execute();
        }
    }
}

/**
 * Liefert das anhand der Version auszuführende SQL-Statement, um diese Version zu erreichen.
 * Die Tabellennamen sind bereits ersetzt.
 */
function getUpdateSQL(int $version): string
{
    global $dbUpdates;
    $resultStatement = "";
    foreach ($dbUpdates[$version] as $i => $s) {
        if ($i % 2 == 1) {
            $resultStatement .= _t($s);
        } else {
            $resultStatement .= $s;
        }
    }
    return $resultStatement;
}

/**
 * Liefert das aktuelle Updatearray zurück
 */
function getUpdateArray(): array
{
    global $dbUpdates;
    return $dbUpdates;
}

/**
 * Liefert das aktuelle Updatearray zurück
 */
function getTestdatenArray(): array
{
    global $testdatensatz;
    return $testdatensatz;
}

function getHoechsteUpdateversion(array $updateArray): int
{
    return count($updateArray) - 1;
}

/**
 * Updated die Datenbank von der angegebenen Version zur höchsten
 */
function updateDb(mysqli $dbConnection, int $vonVersion = 0)
{
    $dbUpdates = getUpdateArray();
    $nachVersion = getHoechsteUpdateversion($dbUpdates);
    for ($i = $vonVersion + 1; $i <= $nachVersion; $i++) {
        $qResult = $dbConnection->query(getUpdateSQL($i));
        if ($qResult === false) {
            throw new Exception($dbConnection->error);
        }
    }
    setDbUpdate($dbConnection, $nachVersion);
}

function setDbUpdate(mysqli $dbConnection, int $version)
{
    $result = $dbConnection->query("UPDATE " . _t("db_config") . " SET wert_i = " . $version . " WHERE parameter = 'version'");
}

/**
 * Überführt den Tabellennamen in die gewünschte Form
 * (setzt das Präfix davor)
 */
function _t(string $tableName): string
{
    global $config_db_tableprefix;
    return $config_db_tableprefix . "_" . $tableName;
}
