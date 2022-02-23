<?php

namespace Arges;

require __DIR__ . '/../vendor/autoload.php';

class BLVeranstaltung
{
    private Factory $factory;

    function __construct(Factory $factory)
    {
        $this->factory = $factory;
    }

    function getVeranstaltungen(): array
    {
        //soll ein Array der TOs zurückgeben
        return $this->factory->getDAOVeranstaltung()->getVeranstaltungen();
    }
}
