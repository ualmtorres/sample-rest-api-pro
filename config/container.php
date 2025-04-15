<?php

use Psr\Container\ContainerInterface;
use App\Infrastructure\Persistence\MySQL\MySQLConnection;
use App\Infrastructure\Repositories\MySQLUserRepository;
use App\Infrastructure\Repositories\MySQLProductRepository;
use App\Application\Services\AuthenticationService;
use App\Infrastructure\Controllers\ProductController;
use App\Infrastructure\Controllers\AuthController;
use App\Infrastructure\Services\MonologLoggerService;
use App\Application\Services\LoggerServiceInterface;

return [
    'db_connection' => function () {
        return MySQLConnection::getInstance();
    },

    'user_repository' => function (ContainerInterface $c) {
        return new MySQLUserRepository();
    },

    'product_repository' => function (ContainerInterface $c) {
        return new MySQLProductRepository();
    },

    'auth_service' => function (ContainerInterface $c) {
        return new AuthenticationService(
            $c->get('user_repository'),
            $_ENV['JWT_SECRET'] ?? null,
            3600
        );
    },

    'logger' => function (ContainerInterface $c) {
        return new MonologLoggerService('api');
    },

    'auth_controller' => function (ContainerInterface $c) {
        return new AuthController(
            $c->get('auth_service'),
            $c->get('logger')
        );
    },

    'product_controller' => function (ContainerInterface $c) {
        return new ProductController(
            $c->get('product_repository'),
            $c->get('logger')
        );
    },
];
