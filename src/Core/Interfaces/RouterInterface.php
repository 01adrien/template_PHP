<?php

namespace Src\Core\Interfaces;

use Psr\Http\Message\ResponseInterface as Response;
use Src\Core\Router\Route;
use Psr\Http\Message\ServerRequestInterface as Request;


interface RouterInterface
{
  public function addRoute(string $path, $callable, string $name, string $httpMethod): self;
  public function match(Request $request): ?Route;
  public function generateUrl(string $name, array $params): string;
  public function redirectByPath(string $path): Response;
  public function redirect(string $path, array $params = []): Response;
}
