<?php

namespace App\Controllers;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use App\Models\Product;

require_once dirname(__DIR__) . '/helpers/helpers.php';
require_once dirname(__DIR__) . '/models/Product.php';

class ProductController
{
    private $mysqli;

    public function __construct($mysqli)
    {
        $this->mysqli = $mysqli;
    }

    public function getAllProducts(RequestInterface $request, ResponseInterface $response, array $args)
    {
        // Consulta para obtener todos los productos
        $query = 'SELECT * FROM product';
        $stmt = $this->mysqli->prepare($query);
        $stmt->execute();
        $result = $stmt->get_result();

        // Comprobar si hay productos
        if ($result->num_rows > 0) {
            $products = [];
            while ($row = $result->fetch_assoc()) {
                $product = new Product($row['id'], $row['name'], $row['price'], $row['created_at'], $row['updated_at']);

                $products[] = $product->toArray();
            }

            // Establecer los datos de la respuesta
            return createJsonResponse($response, [
                'status' => 200,
                'result' => $products
            ]);
        } else {
            // Establecer los datos de la respuesta
            return createJsonResponse($response, [
                'status' => 204,
                'message' => 'No products found'
            ]);
        }
    }

    public function getProductById(RequestInterface $request, ResponseInterface $response, array $args)
    {
        // Obtener el ID del producto
        $productId = $args['id'];

        // Consulta para obtener un producto por ID
        $query = "SELECT * FROM product WHERE id = ?";
        $stmt = $this->mysqli->prepare($query);
        $stmt->bind_param('i', $productId);
        $stmt->execute();
        $result = $stmt->get_result();

        // Comprobar si hay un producto
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $product = new Product($row['id'], $row['name'], $row['price'], $row['created_at'], $row['updated_at']);
            // Establecer los datos de la respuesta
            return createJsonResponse($response, [
                'status' => 200,
                'result' => $product->toArray()
            ]);
        } else {
            // Establecer los datos de la respuesta
            return createJsonResponse($response, [
                'status' => 204,
                'message' => 'Product not found'
            ]);
        }

        // Devolver la respuesta utilizando la función auxiliar
        return createJsonResponse($response, $data);
    }

    public function createProduct(RequestInterface $request, ResponseInterface $response, array $args)
    {
        // Obtener el cuerpo de la solicitud
        $body = $request->getParsedBody();

        // Crear un nuevo producto
        $name = $body['name'];
        $price = $body['price'];
        $createdAt = date('Y-m-d H:i:s');
        $updatedAt = date('Y-m-d H:i:s');

        // Consulta para crear un nuevo producto
        $query = "INSERT INTO product (name, price, created_at, updated_at) VALUES (?, ?, ?, ?)";
        $stmt = $this->mysqli->prepare($query);
        $stmt->bind_param('sdss', $name, $price, $createdAt, $updatedAt);
        $result = $stmt->execute();

        // Comprobar si se ha creado el producto
        if ($result) {
            // Obtener el ID del producto creado
            $productId = $this->mysqli->insert_id;

            // Devolver el producto creado
            return createJsonResponse($response, [
                'status' => 201,
                'message' => 'Product created successfully',
                'result' => [
                    'id' => $productId,
                    'name' => $name,
                    'price' => $price,
                    'created_at' => $createdAt,
                    'updated_at' => $updatedAt
                ]
            ]);
        } else {
            // Establecer los datos de la respuesta
            return createJsonResponse($response, [
                'status' => 400,
                'message' => 'Error creating product'
            ]);
        }
    }

    public function updateProduct(RequestInterface $request, ResponseInterface $response, array $args)
    {
        // Obtener el ID del producto
        $productId = $args['id'];

        // Obtener el cuerpo de la solicitud
        $body = $request->getParsedBody();

        // Comprobar si el producto existe
        $query = "SELECT * FROM product WHERE id = ?";
        $stmt = $this->mysqli->prepare($query);
        $stmt->bind_param('i', $productId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Actualizar el producto

            // Asignar $name y $price si existen, de lo contrario asignar NULL
            $name = isset($body['name']) ? $body['name'] : NULL;
            $price = isset($body['price']) ? $body['price'] : NULL;

            $updatedAt = date('Y-m-d H:i:s');

            // Update the product. COALESCE is used to keep the existing value if the new value is NULL
            $query = "UPDATE product SET name = COALESCE(?, name), price = COALESCE(?, price), updated_at = ? WHERE id = ?";
            $stmt = $this->mysqli->prepare($query);
            $stmt->bind_param('sdsi', $name, $price, $updatedAt, $productId);
            $result = $stmt->execute();

            // Comprobar si se ha actualizado el producto
            if ($result) {
                // Consulta para obtener el producto actualizado
                $query = "SELECT * FROM product WHERE id = ?";
                $stmt = $this->mysqli->prepare($query);
                $stmt->bind_param('i', $productId);
                $stmt->execute();
                $result = $stmt->get_result();
                $row = $result->fetch_assoc();
                $product = new Product($row['id'], $row['name'], $row['price'], $row['created_at'], $row['updated_at']);
                // Devolver el producto actualizado
                return createJsonResponse($response, [
                    'status' => 200,
                    'message' => 'Product updated successfully',
                    'result' => $product->toArray()
                ]);
            } else {
                // Establecer los datos de la respuesta
                return createJsonResponse($response, [
                    'status' => 400,
                    'message' => 'Error updating product'
                ]);
            }
        } else {
            // Establecer los datos de la respuesta
            return createJsonResponse($response, [
                'status' => 204,
                'message' => 'Product not found'
            ]);
        }
    }

    public function deleteProduct(RequestInterface $request, ResponseInterface $response, array $args)
    {
        // Obtener el ID del producto
        $productId = $args['id'];

        // Comprobar si el producto existe
        $query = "SELECT * FROM product WHERE id = ?";
        $stmt = $this->mysqli->prepare($query);
        $stmt->bind_param('i', $productId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Eliminar el producto
            $query = "DELETE FROM product WHERE id = ?";
            $stmt = $this->mysqli->prepare($query);
            $stmt->bind_param('i', $productId);
            $result = $stmt->execute();

            // Comprobar si se ha eliminado el producto
            if ($result) {
                // Establecer los datos de la respuesta
                return createJsonResponse($response, [
                    'status' => 200,
                    'message' => 'Product deleted successfully'
                ]);
            } else {
                // Establecer los datos de la respuesta
                return createJsonResponse($response, [
                    'status' => 400,
                    'message' => 'Error deleting product'
                ]);
            }
        } else {
            // Establecer los datos de la respuesta
            return createJsonResponse($response, [
                'status' => 204,
                'message' => 'Product not found'
            ]);
        }
    }
}
