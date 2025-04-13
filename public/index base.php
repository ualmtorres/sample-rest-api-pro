<?php

require dirname(__DIR__) . '/vendor/autoload.php';

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Slim\Factory\AppFactory;

// Create the application
$app = AppFactory::create();

// Define the application routes
$app->get('/', function (RequestInterface $request, ResponseInterface $response, array $args) {
    // Set the response content type
    header('Content-Type: application/json; charset=utf-8');

    // Set the response status code
    $data['status'] = 200;

    // Set the response data
    $data['message'] = 'Hello from Slim';

    // Write the response
    $response->getBody()->write(json_encode($data));

    // Return the response
    return $response;
});

// Run the application
$app->setBasePath('/' . basename(dirname(__DIR__)));
$app->run();
