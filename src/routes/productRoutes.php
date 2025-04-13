<?php

use App\Controllers\ProductController;
use Slim\Routing\RouteCollectorProxy;

// Crear instancia del controlador
$productController = new ProductController($GLOBALS['mysqli']);

$app->group('/products', function (RouteCollectorProxy $group) use ($productController) {
    // Ruta para obtener todos los productos
    $group->get('', [$productController, 'getAllProducts']);

    // Ruta para obtener un producto por ID
    $group->get('/{id}', [$productController, 'getProductById']);

    // Ruta para crear un nuevo producto
    $group->post('', [$productController, 'createProduct']);

    // Ruta para actualizar un producto por ID
    $group->put('/{id}', [$productController, 'updateProduct']);

    // Ruta para eliminar un producto por ID
    $group->delete('/{id}', [$productController, 'deleteProduct']);
});
