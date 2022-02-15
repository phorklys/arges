<?php
    error_reporting(E_ALL);
    require_once "config.php";
    require_once "db_update_befehle.php";
    print_r($dbUpdates);

    $dbVerbindung = connectDatabase($config_db_server, $config_db_user, $config_db_password, $config_db_database);

    echo "Version: ".getVersion($dbVerbindung)."<br>";
    echo "SQL: ".getUpdateSQL(1);

    function connectDatabase(string $host, string $user, string $password, string $database): mysqli {
        return new mysqli($host, $user, $password, $database);
    }

    function getVersion(mysqli $dbConnection): int {
        $result = $dbConnection->query("SELECT wert_i FROM "._t("db_config")." WHERE parameter = 'version'");
        $value = $result->fetch_assoc();
        if ($value == false || $value == null || $value['wert_i'] == null) {
            return -1;
        }
        return $value['wert_i'];
    }

    /**
     * Liefert das anhand der Version auszuführende SQL-Statement, um diese Version zu erreichen.
     * Die Tabellennamen sind bereits ersetzt.
     */
    function getUpdateSQL(int $version): string {
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

    function updateDb(int $fromVersion = -1) {

    }

    /**
     * Überführt den Tabellennamen in die gewünschte Form
     * (setzt das Präfix davor)
     */
    function _t(string $tableName): string {
        global $config_db_tableprefix;
        return $config_db_tableprefix."_".$tableName;
    }




?>