<?php

namespace Tests\Core\Fixtures;

use Psr\Http\Message\ServerRequestInterface as Request;
use Src\Core\Attributes\Routes\{Route, Base};

#[Base('/test')]
class TestController
{
  public function index(): string
  {
    return 'index page';
  }

  #[Route(path: '/create/post', name: 'post', method: 'POST')]
  public function getPost(Request $request): string
  {
    $body = json_decode((string)$request->getBody());
    return "post with title: $body->title and content: $body->content created";
  }
}
