<?php

namespace Src\App\Controllers;

use Src\Core\Attributes\Routes\Logged;
use Src\Core\Attributes\Routes\Route;
use Src\Core\Attributes\Routes\Base;
use Src\Core\Interfaces\RendererInterface;

#[Base('/user')]
class UserController
{
    public function __construct(private RendererInterface $renderer)
    {
    }
    #[Logged]
    #[Route(path: '/me', name: 'me', method: 'GET')]
    public function profile(): string
    {
        return $this->renderer->render('users/profile');
    }
}
