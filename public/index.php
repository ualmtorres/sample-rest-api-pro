<?php

require dirname(__DIR__) . '/vendor/autoload.php';

use Dotenv\Dotenv;
use App\Infrastructure\Bootstrap\Bootstrap;
use App\Infrastructure\Routes\{AuthRoutes, ProductRoutes, GeneralRoutes};

// Cargar variables de entorno
$dotenv = Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

// Inicializar la aplicación
$bootstrap = new Bootstrap();
$app = $bootstrap->getApp();

// Configurar las rutas
AuthRoutes::setup($app);
ProductRoutes::setup($app);
GeneralRoutes::setup($app);

// Ejecutar la aplicación
$app->run();
