<?php

namespace Arges;

use \mysqli;

class GeneralFactory implements Factory
{
    private array $config;
    private mysqli $dbConnection;
    private string $tablePrefix;

    function __construct(array $config)
    {
        $this->config = $config;
        $this->tablePrefix = $this->setTableprefix();
        $this->dbConnection = $this->connectDatabase();
    }

    public function getServiceWorker(): ServiceWorker
    {
        return new ServiceWorker($this);
    }

    public function getBLVeranstaltung(): BLVeranstaltung
    {
        return new BLVeranstaltung($this);
    }

    public function getDAOVeranstaltung(): DAOVeranstaltung
    {
        return new DAOVeranstaltung($this, $this->dbConnection, $this->tablePrefix);
    }

    function connectDatabase(): mysqli
    {
        return new mysqli($this->config['server'], $this->config['user'], $this->config['password'], $this->config['database']);
    }

    function setTableprefix(): string
    {
        return $this->config['tableprefix'];
    }
}
