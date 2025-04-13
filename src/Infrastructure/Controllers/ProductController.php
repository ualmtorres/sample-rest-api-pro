<?php

namespace App\Infrastructure\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Infrastructure\Repositories\MySQLProductRepository;
use App\Domain\Entities\Product;
use App\Application\Services\ResponseFormatter;

class ProductController
{
    private $repository;

    public function __construct()
    {
        $this->repository = new MySQLProductRepository();
    }

    public function getAll(Request $request, Response $response): Response
    {
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
        $product = $this->repository->findById($id);

        if (!$product) {
            return ResponseFormatter::asJson($response, [
                'status' => 200,
                'message' => 'Product not found'
            ], 200);
        }

        return ResponseFormatter::asJson($response, [
            'status' => 200,
            'result' => $product->jsonSerialize()
        ], 200);
    }

    public function create(Request $request, Response $response): Response
    {
        $data = json_decode($request->getBody(), true);
        $product = new Product($data['name'], $data['price']);
        $this->repository->save($product);

        return ResponseFormatter::asJson($response, [
            'status' => 201,
            'message' => 'Product created successfully',
            'result' => $product->jsonSerialize()
        ], 201);
    }

    public function update(Request $request, Response $response, array $args): Response
    {
        $id = (int)$args['id'];
        $data = json_decode($request->getBody(), true);
        $product = $this->repository->findById($id);

        if (!$product) {
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
        $product = $this->repository->findById($id);

        if (!$product) {
            return ResponseFormatter::asJson($response, [
                'status' => 200,
                'error' => 'Product not found'
            ], 200);
        }

        $this->repository->delete($id);
        return ResponseFormatter::asJson($response, [
            'status' => 200,
            'message' => 'Product deleted successfully'
        ], 200);
    }
}
