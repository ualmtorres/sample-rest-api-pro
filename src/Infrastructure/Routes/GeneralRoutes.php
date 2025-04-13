<?php

namespace App\Infrastructure\Routes;

use Slim\App;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Application\Services\ResponseFormatter;

class GeneralRoutes
{
    public static function setup(App $app): void
    {
        $app->get('/[docs]', function (Request $request, Response $response) {
            $filePath = __DIR__ . '/../../docs/api-docs.html';
            if (!file_exists($filePath)) {
                return ResponseFormatter::asJson($response, [
                    'status' => 404,
                    'message' => 'Documentation not found'
                ], 404);
            }

            $response->getBody()->write(file_get_contents($filePath));
            return $response->withHeader('Content-Type', 'text/html; charset=utf-8');
        });

        $app->get('/test', function (Request $request, Response $response) {
            return ResponseFormatter::asJson($response, [
                'status' => 200,
                'message' => 'Test route'
            ]);
        });

        // Interceptar todas las rutas no definidas 
        $app->any('{routes:.+}', function (Request $request, Response $response) {
            return ResponseFormatter::asJson($response, [
                'status' => 404,
                'message' => 'Route not found'
            ], 404);
        });
    }
}
