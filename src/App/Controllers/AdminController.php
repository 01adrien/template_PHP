<?php

namespace Src\App\Controllers;

use Src\Core\Attributes\Routes\{Admin, Logged, Route};

class AdminController
{
  #[Admin]
  #[Route(path: '/admin', name: 'admin', method: 'GET')]
  public function index()
  {
    return '<h1>admin page</h1>';
  }
}
