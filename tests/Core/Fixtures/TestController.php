<?php

namespace Tests\Core\Fixtures;

use Psr\Http\Message\ServerRequestInterface as Request;
use Src\Core\Attributes\Routes\Route;

class TestController
{
  public function index(): string
  {
    return 'index';
  }

  public function getUser(Request $request): string
  {
    $name = $request->getAttribute('name');
    return "user => $name";
  }

  #[Route(path: '/test/post/{id}', name: 'post', method: 'GET')]
  public function getPost(Request $request): string
  {
    $id = $request->getAttribute('id');
    return "posts id: $id";
  }
}
