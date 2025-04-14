<?php

namespace App\Infrastructure\Routes;

use Slim\App;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Infrastructure\Controllers\AuthController;

class AuthRoutes
{
    public static function setup(App $app): void
    {
        $app->group('/auth', function ($group) {
            $controller = new AuthController();

            $group->post('/register', function (Request $request, Response $response) use ($controller) {
                return $controller->register($request, $response);
            });

            $group->post('/login', function (Request $request, Response $response) use ($controller) {
                return $controller->login($request, $response);
            });
        });
    }
}
