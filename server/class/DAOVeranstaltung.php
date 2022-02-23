<?php

namespace Arges;

use \mysqli;

require __DIR__ . '/../vendor/autoload.php';

class DAOVeranstaltung extends DAO
{
    function getVeranstaltungen(): array
    {
        return $this->_db()->query('SELECT * FROM ' . $this->_t('veranstaltungen') . ';')->fetch_array();
    }
}
