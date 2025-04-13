<?php

namespace App\Models;

class Product
{
    private $id;
    private $name;
    private $price;
    private $created_at;
    private $updated_at;

    public function __construct($id, $name, $price, $created_at = null, $updated_at = null)
    {
        $this->id = $id;
        $this->name = $name;
        $this->price = $price;
        $this->created_at = $created_at;
        $this->updated_at = $updated_at;
    }

    // Method to convert object to array
    public function toArray()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'price' => $this->price,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
