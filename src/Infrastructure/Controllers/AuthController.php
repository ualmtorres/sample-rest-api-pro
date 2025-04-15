<?php

namespace App\Infrastructure\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Application\Services\AuthenticationService;
use App\Application\Services\ResponseFormatter;
use App\Infrastructure\Repositories\MySQLUserRepository;
use App\Application\Services\LoggerServiceInterface;

class AuthController
{
    private $authService;
    private $logger;

    public function __construct(
        AuthenticationService $authService,
        LoggerServiceInterface $logger
    ) {
        $this->authService = $authService;
        $this->logger = $logger;
    }

    public function register(Request $request, Response $response): Response
    {
        try {
            $data = json_decode($request->getBody(), true) ?? [];
            $endpoint = $request->getAttribute('endpoint');

            $errors = $this->validateRegistrationData($data);
            if (!empty($errors)) {
                $this->logger->warning('Intento de registro con datos inválidos', [
                    'endpoint' => $endpoint,
                    'errors' => $errors,
                    'username' => $data['username'] ?? null
                ]);
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

            $this->logger->info('Usuario registrado', [
                'endpoint' => $endpoint,
                'username' => $data['username']
            ]);

            return ResponseFormatter::asJson($response, [
                'status' => 201,
                'message' => 'Usuario registrado correctamente',
                'user' => $user
            ], 201);
        } catch (\Exception $e) {
            $this->logger->error('Error en registro', [
                'endpoint' => $endpoint,
                'error' => $e->getMessage(),
                'username' => $data['username'] ?? null
            ]);

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
            $endpoint = $request->getAttribute('endpoint');

            $errors = $this->validateLoginData($data);
            if (!empty($errors)) {
                $this->logger->warning('Intento de login con datos inválidos', [
                    'endpoint' => $endpoint,
                    'errors' => $errors,
                    'username' => $data['username'] ?? null
                ]);
                return ResponseFormatter::asJson($response, [
                    'status' => 400,
                    'message' => 'Error de validación',
                    'errors' => $errors
                ], 400);
            }

            $result = $this->authService->login($data['username'], $data['password']);

            $this->logger->info('Login exitoso', [
                'endpoint' => $endpoint,
                'username' => $data['username']
            ]);

            return ResponseFormatter::asJson($response, [
                'status' => 200,
                'message' => 'Login correcto',
                'data' => $result
            ]);
        } catch (\Exception $e) {
            $this->logger->error('Error en login', [
                'endpoint' => $endpoint,
                'error' => $e->getMessage(),
                'username' => $data['username'] ?? null
            ]);
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
