<?php

require dirname(__DIR__) . '/vendor/autoload.php';

use Slim\Factory\AppFactory;
use Dotenv\Dotenv;
use App\Infrastructure\Routes\ProductRoutes;
use App\Infrastructure\Routes\GeneralRoutes;

// Cargar variables de entorno
$dotenv = Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

// Crear la aplicación
$app = AppFactory::create();

// Configurar Slim para procesar datos JSON y errores
$app->addBodyParsingMiddleware();
$app->addErrorMiddleware(true, true, true);

// Configurar las rutas usando las clases
ProductRoutes::setup($app);
GeneralRoutes::setup($app);

// Ejecutar la aplicación
$app->run();
