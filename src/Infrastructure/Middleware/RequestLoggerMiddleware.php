<?php

namespace App\Infrastructure\Middleware;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

/**
 * Middleware que extrae y añade el endpoint a los atributos de la request para su uso en el sistema de logging
 */
class RequestLoggerMiddleware
{
    public function __invoke(Request $request, RequestHandler $handler): Response
    {
        $method = $request->getMethod();
        $uri = $request->getUri()->getPath();
        $route = $request->getAttribute('route');

        // Si hay una ruta definida, usar su patrón, si no construir uno basado en el URI
        $endpoint = $route ? $route->getPattern() : $this->normalizeEndpoint($uri);

        // Adjuntar información del endpoint
        $request = $request->withAttribute('endpoint', $endpoint)
            ->withAttribute('request_method', $method);

        return $handler->handle($request);
    }

    /**
     * Normaliza el URI para generar un patrón de endpoint
     */
    private function normalizeEndpoint(string $uri): string
    {
        // Eliminar slash inicial y final si existen
        $uri = trim($uri, '/');

        // Detectar IDs numéricos en la ruta y reemplazarlos por {id}
        return preg_replace('/\/\d+(?=\/|$)/', '/{id}', $uri);
    }
}
