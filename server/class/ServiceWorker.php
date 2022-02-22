<?php

namespace Arges;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

require __DIR__ . '/../vendor/autoload.php';

class ServiceWorker
{
    function __construct()
    {
        echo "Konstruktor aufgerufen";
    }

    public function getVeranstaltungen(Request $request, Response $response, $args): Response
    {
        $response->getBody()->write("Hier sind die Veranstaltungen");
        return $response;
    }
}
