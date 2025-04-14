<?php

use Psr\Container\ContainerInterface;
use App\Infrastructure\Persistence\MySQL\MySQLConnection;
use App\Infrastructure\Repositories\MySQLUserRepository;
use App\Infrastructure\Repositories\MySQLProductRepository;
use App\Application\Services\AuthenticationService;
use App\Infrastructure\Controllers\ProductController;
use App\Infrastructure\Controllers\AuthController;

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

    'auth_controller' => function (ContainerInterface $c) {
        return new AuthController($c->get('auth_service'));
    },

    'product_controller' => function (ContainerInterface $c) {
        return new ProductController($c->get('product_repository'));
    },
];
