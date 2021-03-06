<?php

namespace Arges;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Psr\Log\LoggerInterface;
use \Throwable;

require __DIR__ . '/../vendor/autoload.php';
require '../config.php';

global $config;

$app = AppFactory::create();

$factory = new GeneralFactory($config);
$serviceWorker =  $factory->getServiceWorker();

$app->get('/server/', function (Request $request, Response $response, $args) {
    $response->getBody()->write("Hello world!");
    return $response;
});

$app->get('/server/veranstaltungen', array($serviceWorker, 'getVeranstaltungen'));

$customErrorHandler = function (
    Request $request,
    Throwable $exception,
    bool $displayErrorDetails,
    bool $logErrors,
    bool $logErrorDetails,
    ?LoggerInterface $logger = null
) use ($app) {

    $payload = ['error' => $exception->getMessage()];

    $response = $app->getResponseFactory()->createResponse();
    $response->getBody()->write(
        print_r($request->getServerParams(), true)
        //json_encode($payload, JSON_UNESCAPED_UNICODE)
    );

    return $response;
};





$errorMiddleware = $app->addErrorMiddleware(true, true, true);
//$errorMiddleware->setDefaultErrorHandler($customErrorHandler);

$app->run();
