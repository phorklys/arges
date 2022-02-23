<?php

namespace Arges;

interface Factory
{
    public function getServiceWorker(): ServiceWorker;

    public function getBLVeranstaltung(): BLVeranstaltung;

    public function getDAOVeranstaltung(): DAOVeranstaltung;
}
