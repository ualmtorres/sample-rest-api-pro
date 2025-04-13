<?php

namespace App\Infrastructure\Repositories;

use App\Domain\Repositories\ProductRepositoryInterface;
use App\Domain\Entities\Product;
use App\Infrastructure\Persistence\MySQL\MySQLConnection;

class MySQLProductRepository implements ProductRepositoryInterface
{
    private $connection;

    public function __construct()
    {
        $this->connection = MySQLConnection::getInstance();
    }

    public function findAll(): array
    {
        $query = 'SELECT * FROM product';
        $result = $this->connection->query($query);
        $products = [];

        while ($row = $result->fetch_assoc()) {
            $products[] = $this->createProductFromRow($row);
        }

        return $products;
    }

    public function findById(int $id): ?Product
    {
        $query = 'SELECT * FROM product WHERE id = ?';
        $stmt = $this->connection->prepare($query);
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            return $this->createProductFromRow($row);
        }

        return null;
    }

    public function save(Product $product): Product
    {
        $query = 'INSERT INTO product (name, price) VALUES (?, ?)';
        $stmt = $this->connection->prepare($query);
        $name = $product->getName();
        $price = $product->getPrice();
        $stmt->bind_param('sd', $name, $price);
        $stmt->execute();

        $product->setId($this->connection->insert_id);
        return $product;
    }

    public function update(Product $product): bool
    {
        $query = 'UPDATE product SET name = ?, price = ? WHERE id = ?';
        $stmt = $this->connection->prepare($query);
        $name = $product->getName();
        $price = $product->getPrice();
        $id = $product->getId();
        $stmt->bind_param('sdi', $name, $price, $id);

        return $stmt->execute();
    }

    public function delete(int $id): bool
    {
        $query = 'DELETE FROM product WHERE id = ?';
        $stmt = $this->connection->prepare($query);
        $stmt->bind_param('i', $id);

        return $stmt->execute();
    }

    private function createProductFromRow(array $row): Product
    {
        $product = new Product($row['name'], (float)$row['price']);
        $product->setId((int)$row['id']);
        $product->setCreatedAt($row['created_at']);
        $product->setUpdatedAt($row['updated_at']);
        return $product;
    }
}
