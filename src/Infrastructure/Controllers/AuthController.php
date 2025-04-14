<?php

namespace App\Infrastructure\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Application\Services\AuthenticationService;
use App\Application\Services\ResponseFormatter;
use App\Infrastructure\Repositories\MySQLUserRepository;

class AuthController
{
    private $authService;

    public function __construct()
    {
        $this->authService = new AuthenticationService(new MySQLUserRepository());
    }

    public function register(Request $request, Response $response): Response
    {
        try {
            $data = json_decode($request->getBody(), true) ?? [];

            $errors = $this->validateRegistrationData($data);
            if (!empty($errors)) {
                return ResponseFormatter::asJson($response, [
                    'status' => 400,
                    'message' => 'Error de validación',
                    'errors' => $errors
                ], 400);
            }

            $user = $this->authService->register(
                $data['username'],
                $data['email'],
                $data['password']
            );

            return ResponseFormatter::asJson($response, [
                'status' => 201,
                'message' => 'Usuario registrado correctamente',
                'user' => $user
            ], 201);
        } catch (\Exception $e) {
            return ResponseFormatter::asJson($response, [
                'status' => 400,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    public function login(Request $request, Response $response): Response
    {
        try {
            $data = json_decode($request->getBody(), true) ?? [];

            $errors = $this->validateLoginData($data);
            if (!empty($errors)) {
                return ResponseFormatter::asJson($response, [
                    'status' => 400,
                    'message' => 'Error de validación',
                    'errors' => $errors
                ], 400);
            }

            $result = $this->authService->login($data['username'], $data['password']);

            return ResponseFormatter::asJson($response, [
                'status' => 200,
                'message' => 'Login correcto',
                'data' => $result
            ]);
        } catch (\Exception $e) {
            return ResponseFormatter::asJson($response, [
                'status' => 401,
                'message' => $e->getMessage()
            ], 401);
        }
    }

    private function validateRegistrationData(array $data): array
    {
        $errors = [];

        if (empty($data['username'])) {
            $errors['username'] = 'El nombre de usuario es requerido';
        }
        if (empty($data['email'])) {
            $errors['email'] = 'El email es requerido';
        }
        if (empty($data['password'])) {
            $errors['password'] = 'La contraseña es requerida';
        }

        return $errors;
    }

    private function validateLoginData(array $data): array
    {
        $errors = [];

        if (empty($data['username'])) {
            $errors['username'] = 'El nombre de usuario es requerido';
        }
        if (empty($data['password'])) {
            $errors['password'] = 'La contraseña es requerida';
        }

        return $errors;
    }
}
