<?php

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

require_once dirname(__DIR__) . '/helpers/helpers.php';

// Rutas de documentación
$app->get('/[docs]', function (RequestInterface $request, ResponseInterface $response) {
    // Cargar el archivo de documentación disponible en src/docs/api-docs.html
    $filePath = __DIR__ . '/../docs/api-docs.html';
    if (file_exists($filePath)) {
        $response->getBody()->write(file_get_contents($filePath));
        return $response->withHeader('Content-Type', 'text/html; charset=utf-8');
    } else {
        return createJsonResponse($response->withStatus(404), [
            'status' => 404,
            'message' => 'Documentation not found'
        ]);
    }
});

// Ruta test
$app->get('/test', function (RequestInterface $request, ResponseInterface $response, array $args) {
    $data = ['status' => 200, 'message' => 'Test route'];
    return createJsonResponse($response, $data);
});

// Interceptar todas las rutas no definidas 
$app->any('{routes:.+}', function (RequestInterface $request, ResponseInterface $response) {
    $data = [
        'status' => 404,
        'message' => 'Route not found'
    ];
    return createJsonResponse($response->withStatus(404), $data);
});
