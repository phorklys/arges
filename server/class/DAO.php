<?php

namespace Arges;

use \mysqli;

require __DIR__ . '/../vendor/autoload.php';

class DAO
{
    private Factory $factory;
    private mysqli $dbConnection;
    private string $dbTableprefix;

    function __construct(Factory $factory, mysqli $dbConnection, string $dbTableprefix)
    {
        $this->factory = $factory;
        $this->dbConnection = $dbConnection;
        $this->dbTableprefix = $dbTableprefix;
    }

    /**
     * Liefert die Datenbankverbindung zurück
     */
    public function _db(): mysqli
    {
        return $this->dbConnection;
    }

    /**
     * Überführt den Tabellennamen in die gewünschte Form
     * (setzt das Präfix davor)
     */
    public function _t(string $tableName): string
    {
        return $this->dbTableprefix . "_" . $tableName;
    }
}
