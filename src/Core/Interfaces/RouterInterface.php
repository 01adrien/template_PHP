<?php

namespace Src\Core\Interfaces;

use Src\Core\Router\Route;
use Psr\Http\Message\ServerRequestInterface as Request;

interface RouterInterface
{
  public function addRoute(string $path, $callable, string $name, string $httpMethod): void;

  public function match(Request $request): ?Route;

  public function generateUrl(string $name, array $params): string;
}
