<?php

namespace Src\App\Controllers;

use Src\Core\Attributes\Routes\Route;
use Src\Core\Interfaces\RendererInterface;

class DashboardController
{
  public function __construct(private RendererInterface $renderer)
  {
  }

  #[Route(path: '/', name: 'dashboard', method: 'GET')]
  public function index(): string
  {
    return $this->renderer->render('index');
  }
}
