<?php

namespace Src\App\Services;

use GuzzleHttp\Psr7\Stream;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\StreamInterface;

class ResponseService
{
  public function jsonResponse(int $statusCode, array $data): Response
  {
    return new \GuzzleHttp\Psr7\Response($statusCode, [], json_encode($data));
  }

  public function plainTextResponse(int $statusCode, string $text): Response
  {
    return new \GuzzleHttp\Psr7\Response($statusCode, [], $text);
  }

  public function notFound404(): Response
  {
    return $this->plainTextResponse(404, require VIEWS_DIR . '/errors/404.html');
  }

  public function FileStreamResponse($fileContent, $filename, $fileMimeType): Response
  {
    return (new \GuzzleHttp\Psr7\Response())
      ->withHeader('Content-Disposition', 'inline; filename="' . $filename . '' . '')
      ->withHeader('Content-Type', $fileMimeType)
      ->withBody(new Stream($fileContent))
      ->withStatus(200);
  }
}
