<?php

namespace App\Application\Services;

use Firebase\JWT\JWT;
use App\Domain\Repositories\UserRepositoryInterface;
use App\Domain\Entities\User;

class AuthenticationService
{
    private $userRepository;
    private string $jwtSecret;
    private int $jwtExpiration;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
        $this->jwtSecret = $_ENV['JWT_SECRET'];
        $this->jwtExpiration = 3600; // 1 hora
    }

    public function register(string $username, string $email, string $password): User
    {
        if ($this->userRepository->findByUsername($username)) {
            throw new \Exception('Username already exists');
        }
        if ($this->userRepository->findByEmail($email)) {
            throw new \Exception('Email already exists');
        }

        $user = new User($username, $email, password_hash($password, PASSWORD_DEFAULT));
        $this->userRepository->save($user);
        return $this->userRepository->findByUsername($username); // Return the user object after saving to get the date values
    }

    public function login(string $username, string $password): array
    {
        $user = $this->userRepository->findByUsername($username);
        if (!$user || !password_verify($password, $user->getPassword())) {
            throw new \Exception('Invalid credentials');
        }

        if (!$user->isActive()) {
            throw new \Exception('User account is disabled');
        }

        $roles = $this->userRepository->getUserRoles($user->getId());
        $token = $this->generateToken($user, $roles);

        return [
            'user' => $user->jsonSerialize(),
            'token' => $token
        ];
    }

    private function generateToken(User $user, array $roles): string
    {
        $payload = [
            'sub' => $user->getId(),
            'username' => $user->getUsername(),
            'roles' => $roles,
            'iat' => time(),
            'exp' => time() + $this->jwtExpiration
        ];

        return JWT::encode($payload, $this->jwtSecret, 'HS256');
    }
}
