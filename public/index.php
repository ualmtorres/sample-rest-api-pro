<?php

require dirname(__DIR__) . '/vendor/autoload.php';
require dirname(__DIR__) . '/db.php';

use Slim\Factory\AppFactory;

// Crear la aplicación
$app = AppFactory::create();

// Configurar Slim para procesar datos JSON
$app->addBodyParsingMiddleware();

// Cargar las rutas desde un archivo separado
require dirname(__DIR__) . '/src/routes/productRoutes.php';
require dirname(__DIR__) . '/src/routes/generalRoutes.php';

// Ejecutar la aplicación
$app->run();
