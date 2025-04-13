<?php

use Psr\Http\Message\ResponseInterface;

// Helper function to handle the response
function createJsonResponse(ResponseInterface $response, array $data): ResponseInterface
{
    // Set the response content type
    $response = $response->withHeader('Content-Type', 'application/json; charset=utf-8');

    // Write the response
    $response->getBody()->write(json_encode($data));

    // Return the response
    return $response;
}
