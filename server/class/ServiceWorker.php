<?php

namespace Arges;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

require __DIR__ . '/../vendor/autoload.php';

class ServiceWorker
{
    private Factory $factory;

    function __construct(Factory $factory)
    {
        $this->factory = $factory;
    }

    public function getVeranstaltungen(Request $request, Response $response, $args): Response
    {
        $veranstaltungen = $this->factory->getBLVeranstaltung()->getVeranstaltungen();
        $response->getBody()->write(json_encode($veranstaltungen));
        return $response;
    }
}
