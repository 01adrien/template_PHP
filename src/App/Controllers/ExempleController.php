<?php

namespace Src\App\Controllers;

use PDO;
use Src\App\Models\ExempleModel;
use Src\Core\Attributes\Routes\{Base, Route};
use Src\Core\Interfaces\RendererInterface;

#[Base('/exemple')]
class ExempleController
{

  public function __construct(
    private RendererInterface $renderer,
    private ExempleModel $exempleModel,
    private PDO $pdo
  ) {
  }

  #[Route(name: 'hello', path: '/hello', method: 'GET')]
  public function index()
  {
    return "<h1>hello</h1>";
  }

  #[Route(name: 'pdo', path: '/pdo', method: 'GET')]
  public function pdo()
  {
    return $this
      ->pdo
      ->query('SELECT database()')
      ->fetchColumn();;
  }

  #[Route(name: 'posts', path: '/posts', method: 'GET')]
  public function posts()
  {
    return $this->renderer->render('exemple', [
      'items' => $this->exempleModel->all()
    ]);
  }
}
