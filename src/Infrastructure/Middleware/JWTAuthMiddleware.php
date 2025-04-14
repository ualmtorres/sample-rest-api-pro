<?php

namespace App\Infrastructure\Middleware;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Psr\Http\Message\ResponseFactoryInterface;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Firebase\JWT\ExpiredException;

class JWTAuthMiddleware
{
    private ResponseFactoryInterface $responseFactory;

    public function __construct(ResponseFactoryInterface $responseFactory)
    {
        $this->responseFactory = $responseFactory;
    }

    public function __invoke(Request $request, RequestHandler $handler): Response
    {
        $token = $this->extractToken($request);
        if (!$token) {
            return $this->unauthorized('Token not provided');
        }

        try {
            $jwt = JWT::decode($token, new Key($_ENV['JWT_SECRET'], 'HS256'));
            $request = $request->withAttribute('jwt', $jwt);
            return $handler->handle($request);
        } catch (ExpiredException $e) {
            return $this->unauthorized('Token expired');
        } catch (\Exception $e) {
            return $this->unauthorized('Invalid token');
        }
    }

    private function extractToken(Request $request): ?string
    {
        $authHeader = $request->getHeaderLine('Authorization');
        if (!empty($authHeader) && preg_match('/Bearer\s+(.*)$/i', $authHeader, $matches)) {
            return $matches[1];
        }
        return null;
    }

    private function unauthorized(string $message = ''): Response
    {
        $response = $this->responseFactory->createResponse(401)
            ->withHeader('Content-Type', 'application/json');

        $response->getBody()->write(json_encode([
            'status' => 401,
            'message' => $message
        ]));

        return $response;
    }
}
