<?php

namespace App\Infrastructure\Routes;

use Slim\App;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Infrastructure\Controllers\ProductController;
use App\Infrastructure\Middleware\JWTAuthMiddleware;

class ProductRoutes
{
    public static function setup(App $app): void
    {
        $responseFactory = $app->getResponseFactory();

        $app->group('/products', function ($group) use ($responseFactory) {
            $controller = new ProductController();
            $jwtMiddleware = new JWTAuthMiddleware($responseFactory);

            // Rutas públicas (sin autenticación)
            $group->get('', function (Request $request, Response $response) use ($controller) {
                return $controller->getAll($request, $response);
            });

            $group->get('/{id}', function (Request $request, Response $response, array $args) use ($controller) {
                return $controller->getById($request, $response, $args);
            });

            // Rutas protegidas (requieren token JWT)
            $group->post('', function (Request $request, Response $response) use ($controller) {
                return $controller->create($request, $response);
            })->add($jwtMiddleware);

            $group->put('/{id}', function (Request $request, Response $response, array $args) use ($controller) {
                return $controller->update($request, $response, $args);
            })->add($jwtMiddleware);

            $group->delete('/{id}', function (Request $request, Response $response, array $args) use ($controller) {
                return $controller->delete($request, $response, $args);
            })->add($jwtMiddleware);
        });
    }
}
