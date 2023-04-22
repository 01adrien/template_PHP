<?php

namespace Src\App\Controllers;

use Psr\Http\Message\ServerRequestInterface as Request;
use Src\App\Entities\ExempleEntity;
use Src\App\Models\ExempleModel;
use Src\Core\Attributes\Routes\Base;
use Src\Core\Attributes\Routes\Route;
use Src\Core\Interfaces\CacheInterface;
use Src\Core\Interfaces\RendererInterface;

#[Base('/exemple')]
class ExempleController
{
    public function __construct(
        private RendererInterface $renderer,
        private ExempleModel $exempleModel,
        private ExempleEntity $exempleEntity,
        private CacheInterface $cache,
    ) {
    }

    #[Route(name: 'all', path: '/all', method: 'GET')]
    public function all(): string
    {
      /** @var array<ExempleEntity> $items */
        $items = $this->exempleModel->all();
        return $this
        ->renderer
        ->render('exemple', [
        'items' => $items
        ]);
    }

    #[Route(name: 'home', path: '/home', method: 'GET')]
    public function home(): string
    {
        return $this->renderer->render('index');
    }

    #[Route(name: 'one', path: '/one/{id}', method: 'GET')]
    public function one(Request $request): string
    {
        $id = ['id' => $request->getAttribute('id')];
      /**  @var ExempleEntity $entity */
        $entity = $this->exempleModel->one($id);

        $cacheKey = "exemple_{$entity->id}_{$entity->updated_at}";

        if ($entity->id && !$this->cache->has($cacheKey)) {
            $date = $entity->getDateObject($entity->created_at);
            $this->cache->put($cacheKey, "
      <div>
        <h1>cached 12345dddEE78d6789</h1>
        <h1>{$entity->title}</h1>
        <p>{$entity->content}</p>
        <p>{$date->format('d-m-Y')}</p>
      </div>
    ");
        }

        return $this->cache->get($cacheKey) ?? "<h1>no record found</h1>";
    }

    #[Route(name: 'create', path: '/create', method: 'POST')]
    public function create(Request $request): string
    {
        
        $body = json_decode($request->getBody()->getContents());
        $this
        ->exempleEntity
        ->setTitle($body->title)
        ->setContent($body->content)
        ->setCreatedAt(new \DateTime('now'));

        $isValid = $this
        ->exempleModel
        ->validate($this->exempleEntity);

        $errors = $isValid->getErrors();
        if (!$errors &&
        $this
        ->exempleModel
        ->insert((array)$this->exempleEntity)
        ) {
            return "<h1>created</h1>";
        };
        return json_encode($errors);
    }

    #[Route(name: 'update', path: '/update', method: 'POST')]
    public function update(Request $request): string
    {
        $body = json_decode($request->getBody()->getContents());

      /**  @var ExempleEntity $entity */
        $entity = $this->exempleModel->one($body->id);

        if (!$entity->id) {
            return "<h1>no record found</h1>";
        }

        foreach ($body as $prop => $value) {
            $entity->$prop = $value;
        }
        $isValid = $this
        ->exempleModel
        ->validate($entity);

        $errors = $isValid->getErrors();

        if (!$errors &&
        $this
        ->exempleModel
        ->update($body->id, (array)$entity)
        ) {
            return  "<h1>updated</h1>";
        };
        return json_encode($errors);
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