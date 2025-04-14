<?php

namespace App\Infrastructure\Routes;

use Slim\App;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Infrastructure\Middleware\JWTAuthMiddleware;

class ProductRoutes
{
    public static function setup(App $app): void
    {
        $container = $app->getContainer();
        $responseFactory = $app->getResponseFactory();

        $app->group('/products', function ($group) use ($container, $responseFactory) {
            $controller = $container->get('product_controller');
            $jwtMiddleware = new JWTAuthMiddleware($responseFactory);

            // Rutas públicas
            $group->get('', [$controller, 'getAll']);
            $group->get('/{id}', [$controller, 'getById']);

            // Rutas protegidas
            $group->post('', [$controller, 'create'])->add($jwtMiddleware);
            $group->put('/{id}', [$controller, 'update'])->add($jwtMiddleware);
            $group->delete('/{id}', [$controller, 'delete'])->add($jwtMiddleware);
        });
    }
}
