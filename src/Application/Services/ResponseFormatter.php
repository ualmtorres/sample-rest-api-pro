<?php

namespace App\Application\Services;

use Psr\Http\Message\ResponseInterface;

class ResponseFormatter
{
    public static function asJson(ResponseInterface $response, array $data, int $status = 200): ResponseInterface
    {
        $response = $response->withHeader('Content-Type', 'application/json; charset=utf-8');
        $response = $response->withStatus($status);
        $response->getBody()->write(json_encode($data));
        return $response;
    }
}
