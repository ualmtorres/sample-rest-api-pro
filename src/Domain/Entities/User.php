<?php

namespace App\Domain\Entities;

class User implements \JsonSerializable
{
    private ?int $id;
    private string $username;
    private string $email;
    private string $password;
    private bool $isActive;
    private ?string $createdAt;
    private ?string $updatedAt;
    private array $roles = [];

    public function __construct(string $username, string $email, string $password)
    {
        $this->id = null;
        $this->username = $username;
        $this->email = $email;
        $this->password = $password;
        $this->isActive = true;
        $this->createdAt = null;
        $this->updatedAt = null;
        $this->roles = [];
    }

    // Getters
    public function getId(): ?int
    {
        return $this->id;
    }
    public function getUsername(): string
    {
        return $this->username;
    }
    public function getEmail(): string
    {
        return $this->email;
    }
    public function getPassword(): string
    {
        return $this->password;
    }
    public function isActive(): bool
    {
        return $this->isActive;
    }
    public function getCreatedAt(): ?string
    {
        return $this->createdAt;
    }
    public function getUpdatedAt(): ?string
    {
        return $this->updatedAt;
    }
    public function getRoles(): array
    {
        return $this->roles;
    }

    // Setters
    public function setId(int $id): void
    {
        $this->id = $id;
    }
    public function setUsername(string $username): void
    {
        $this->username = $username;
    }
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }
    public function setPassword(string $password): void
    {
        $this->password = $password;
    }
    public function setIsActive(bool $isActive): void
    {
        $this->isActive = $isActive;
    }
    public function setCreatedAt(?string $createdAt): void
    {
        $this->createdAt = $createdAt;
    }
    public function setUpdatedAt(?string $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }
    public function setRoles(array $roles): void
    {
        $this->roles = $roles;
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'username' => $this->username,
            'email' => $this->email,
            'is_active' => $this->isActive,
            'roles' => $this->roles,
            'created_at' => $this->createdAt,
            'updated_at' => $this->updatedAt
        ];
    }
}
