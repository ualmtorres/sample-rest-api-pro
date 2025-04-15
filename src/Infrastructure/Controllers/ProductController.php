<?php

namespace App\Infrastructure\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Infrastructure\Repositories\MySQLProductRepository;
use App\Domain\Entities\Product;
use App\Application\Services\ResponseFormatter;
use App\Application\Services\LoggerServiceInterface;

class ProductController
{
    private $repository;
    private $logger;

    public function __construct(
        MySQLProductRepository $repository,
        LoggerServiceInterface $logger
    ) {
        $this->repository = $repository;
        $this->logger = $logger;
    }

    public function getAll(Request $request, Response $response): Response
    {
        $endpoint = $request->getAttribute('endpoint');
        $this->logger->info('Consultando todos los productos', [
            'endpoint' => $endpoint
        ]);
        $products = $this->repository->findAll();
        return ResponseFormatter::asJson(
            $response,
            [
                'status' => 200,
                'message' => $products
            ]
        );
    }

    public function getById(Request $request, Response $response, array $args): Response
    {
        $id = (int)$args['id'];
        $endpoint = $request->getAttribute('endpoint');
        $this->logger->info('Consultando producto', [
            'endpoint' => $endpoint,
            'id' => $id
        ]);

        $product = $this->repository->findById($id);

        if (!$product) {
            $this->logger->warning('Producto no encontrado', [
                'endpoint' => $endpoint,
                'id' => $id
            ]);
            return ResponseFormatter::asJson($response, [
                'status' => 404,
                'message' => 'Product not found'
            ], 404);
        }

        return ResponseFormatter::asJson($response, [
            'status' => 200,
            'result' => $product->jsonSerialize()
        ], 200);
    }

    public function create(Request $request, Response $response): Response
    {
        try {
            $endpoint = $request->getAttribute('endpoint');
            $data = json_decode($request->getBody(), true);
            $product = new Product($data['name'], $data['price']);
            $this->repository->save($product);

            $this->logger->info('Producto creado', [
                'endpoint' => $endpoint,
                'id' => $product->getId(),
                'name' => $product->getName()
            ]);

            return ResponseFormatter::asJson($response, [
                'status' => 201,
                'message' => 'Product created successfully',
                'result' => $product->jsonSerialize()
            ], 201);
        } catch (\Exception $e) {
            $this->logger->error('Error creando producto', [
                'endpoint' => $request->getAttribute('endpoint'),
                'error' => $e->getMessage(),
                'data' => $data ?? null
            ]);

            return ResponseFormatter::asJson($response, [
                'status' => 400,
                'message' => 'Error creating product'
            ], 400);
        }
    }

    public function update(Request $request, Response $response, array $args): Response
    {
        $id = (int)$args['id'];
        $endpoint = $request->getAttribute('endpoint');
        $this->logger->info('Actualizando producto', [
            'endpoint' => $endpoint,
            'id' => $id
        ]);

        $data = json_decode($request->getBody(), true);
        $product = $this->repository->findById($id);

        if (!$product) {
            $this->logger->warning('Intento de actualizar producto inexistente', [
                'endpoint' => $endpoint,
                'id' => $id
            ]);
            return ResponseFormatter::asJson($response, [
                'status' => 200,
                'message' => 'Product not found'
            ], 200);
        }

        if (isset($data['name'])) $product->setName($data['name']);
        if (isset($data['price'])) $product->setPrice($data['price']);
        $this->repository->update($product);

        return ResponseFormatter::asJson($response, [
            'status' => 200,
            'message' => 'Product updated successfully',
            'result' => $product->jsonSerialize()
        ], 200);
    }

    public function delete(Request $request, Response $response, array $args): Response
    {
        $id = (int)$args['id'];
        $endpoint = $request->getAttribute('endpoint');
        $this->logger->info('Eliminando producto', [
            'endpoint' => $endpoint,
            'id' => $id
        ]);

        $product = $this->repository->findById($id);

        if (!$product) {
            $this->logger->warning('Intento de eliminar producto inexistente', [
                'endpoint' => $endpoint,
                'id' => $id
            ]);
            return ResponseFormatter::asJson($response, [
                'status' => 404,
                'error' => 'Product not found'
            ], 404);
        }

        $this->repository->delete($id);
        $this->logger->info('Producto eliminado', [
            'endpoint' => $endpoint,
            'id' => $id
        ]);

        return ResponseFormatter::asJson($response, [
            'status' => 200,
            'message' => 'Product deleted successfully'
        ], 200);
    }
}
