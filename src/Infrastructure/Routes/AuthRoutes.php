<?php

namespace App\Infrastructure\Routes;

use Slim\App;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class AuthRoutes
{
    public static function setup(App $app): void
    {
        $container = $app->getContainer();

        $app->group('/auth', function ($group) use ($container) {
            $controller = $container->get('auth_controller');

            $group->post('/register', [$controller, 'register']);
            $group->post('/login', [$controller, 'login']);
        });
    }
}
