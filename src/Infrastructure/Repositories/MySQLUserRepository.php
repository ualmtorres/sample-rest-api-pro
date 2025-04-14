<?php

namespace App\Infrastructure\Repositories;

use App\Domain\Repositories\UserRepositoryInterface;
use App\Domain\Entities\User;
use App\Infrastructure\Persistence\MySQL\MySQLConnection;

class MySQLUserRepository implements UserRepositoryInterface
{
    private $connection;

    public function __construct()
    {
        $this->connection = MySQLConnection::getInstance();
    }

    public function findByUsername(string $username): ?User
    {
        $query = "SELECT * FROM auth_api.users WHERE username = ?";
        $stmt = $this->connection->prepare($query);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            return $this->createUserFromRow($row);
        }

        return null;
    }

    public function findByEmail(string $email): ?User
    {
        $query = "SELECT * FROM auth_api.users WHERE email = ?";
        $stmt = $this->connection->prepare($query);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            return $this->createUserFromRow($row);
        }

        return null;
    }

    public function save(User $user): void
    {
        $query = "INSERT INTO auth_api.users (username, email, password_hash) VALUES (?, ?, ?)";
        $stmt = $this->connection->prepare($query);
        $username = $user->getUsername();
        $email = $user->getEmail();
        $password = $user->getPassword();
        $stmt->bind_param("sss", $username, $email, $password);
        $stmt->execute();

        $user->setId($this->connection->insert_id);
    }

    public function getUserRoles(int $userId): array
    {
        $query = "SELECT r.name FROM auth_api.roles r 
                 INNER JOIN auth_api.user_roles ur ON r.id = ur.role_id 
                 WHERE ur.user_id = ?";
        $stmt = $this->connection->prepare($query);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();

        $roles = [];
        while ($row = $result->fetch_assoc()) {
            $roles[] = $row['name'];
        }

        return $roles;
    }

    private function createUserFromRow(array $row): User
    {
        $user = new User($row['username'], $row['email'], $row['password_hash']);
        $user->setId($row['id']);
        $user->setIsActive($row['is_active']);
        $user->setCreatedAt($row['created_at']);
        $user->setUpdatedAt($row['updated_at']);
        $user->setRoles($this->getUserRoles($row['id']));
        return $user;
    }
}
