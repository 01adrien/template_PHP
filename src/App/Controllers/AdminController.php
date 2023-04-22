<?php

namespace Src\App\Controllers;

use Src\Core\Attributes\Routes\Admin;
use Src\Core\Attributes\Routes\Logged;
use Src\Core\Attributes\Routes\Route;

class AdminController
{
    #[Admin]
    #[Route(path: '/admin', name: 'admin', method: 'GET')]
    public function index(): string
    {
        return '<h1>admin page</h1>';
    }
}
