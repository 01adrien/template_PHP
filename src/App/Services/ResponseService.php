<?php

namespace Src\App\Services;

use GuzzleHttp\Psr7\Stream;
use GuzzleHttp\Psr7\Response as Psr7Response;
use Psr\Http\Message\ResponseInterface as Response;

class ResponseService
{
    public function jsonResponse(int $statusCode, array $data): Response
    {
        return new Psr7Response($statusCode, [], json_encode($data));
    }

    public function plainTextResponse(int $statusCode, string $text): Response
    {
        return new Psr7Response($statusCode, [], $text);
    }

    public function notFound404(): Response
    {
        return $this->plainTextResponse(404, require VIEWS_DIR . '/html/errors/404.html');
    }

    public function notAuthorized403(): Response
    {
        return $this->plainTextResponse(404, require VIEWS_DIR . '/html/errors/403.html');
    }

    public function FileStreamResponse(mixed $fileContent, string $filename, string $fileMimeType): Response
    {
        return (new Psr7Response())
        ->withHeader('Content-Disposition', 'inline; filename="' . $filename . '' . '')
        ->withHeader('Content-Type', $fileMimeType)
        ->withBody(new Stream($fileContent))
        ->withStatus(200);
    }
}