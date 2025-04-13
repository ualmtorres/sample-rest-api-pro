<?php

namespace App\Infrastructure\Routes;

use Slim\App;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Infrastructure\Controllers\ProductController;

class ProductRoutes
{
    public static function setup(App $app): void
    {
        $app->group('/products', function ($group) {
            $controller = new ProductController();

            $group->get('', function (Request $request, Response $response) use ($controller) {
                return $controller->getAll($request, $response);
            });

            $group->get('/{id}', function (Request $request, Response $response, array $args) use ($controller) {
                return $controller->getById($request, $response, $args);
            });

            $group->post('', function (Request $request, Response $response) use ($controller) {
                return $controller->create($request, $response);
            });

            $group->put('/{id}', function (Request $request, Response $response, array $args) use ($controller) {
                return $controller->update($request, $response, $args);
            });

            $group->delete('/{id}', function (Request $request, Response $response, array $args) use ($controller) {
                return $controller->delete($request, $response, $args);
            });
        });
    }
}
