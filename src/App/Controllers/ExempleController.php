<?php

namespace Src\App\Controllers;

use Psr\Http\Message\ServerRequestInterface as Request;
use Src\App\Entities\ExempleEntity;
use Src\App\Models\ExempleModel;
use Src\Core\Attributes\Routes\{Base, Route};
use Src\Core\Interfaces\RendererInterface;

#[Base('/exemple')]
class ExempleController
{
  public function __construct(
    private RendererInterface $renderer,
    private ExempleModel $exempleModel,
    private ExempleEntity $exempleEntity,
  ) {
  }

  #[Route(name: 'all', path: '/all', method: 'GET')]
  public function all(): string
  {
    return $this
      ->renderer
      ->render('exemple', [
        'items' => $this->exempleModel->all()
      ]);
  }

  #[Route(name: 'one', path: '/one/{id}', method: 'GET')]
  public function one(Request $request): string
  {
    /**  @var ExempleEntity $data */
    $data = $this
      ->exempleModel
      ->one($request->getAttribute('id'));
    if ($data) {
      return "
      <div>
        <h1>{$data->title}</h1>
        <p>{$data->content}</p>
        <p>{$data->created_at->format('Y-m-d H:i:s')}</p>

      </div>
    ";
    }
    return "<h1>no record found</h1>";
  }

  #[Route(name: 'create', path: '/create', method: 'POST')]
  public function create(): string
  {
    $this
      ->exempleEntity
      ->setTitle('title 450')
      ->setContent('super content 450')
      ->setCreatedAt(new \DateTime('now'));

    if ($this
      ->exempleModel
      ->insert((array)$this->exempleEntity)
    ) {
      return "<h1>created</h1>";
    };
    return "<h1>issue with creation</h1>";
  }

  #[Route(name: 'update', path: '/update/{id}', method: 'POST')]
  public function update(Request $request): string
  {
    if ($this
      ->exempleModel
      ->update(
        $request->getAttribute('id'),
        [
          'title' => 'my super title',
          'content' => 'my super content'
        ]
      )
    ) {
      return  "<h1>updated</h1>";
    };
    return "<h1>issue with update</h1>";
  }

  #[Route(name: 'delete', path: '/delete/{id}', method: 'DELETE')]
  public function delete(Request $request): string
  {
    if ($this
      ->exempleModel
      ->delete($request->getAttribute('id'))
    ) {
      return '<h1>deleted</h1>';
    }
    return "<h1>issue with delete</h1>";
  }
}
