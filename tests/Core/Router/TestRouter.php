<?php

namespace Tests\Core\Router;

use GuzzleHttp\Psr7\ServerRequest;
use PHPUnit\Framework\TestCase;
use Src\Core\Router\Router;
use Tests\Core\Fixtures\ControllerFixture;
use Tests\Core\Fixtures\TestController;

class TestRouter extends TestCase
{
  private Router $router;

  public function setUp(): void
  {
    $this->router = new Router();
  }

  public function test_get_route()
  {
    $req = new ServerRequest('GET', '/test');
    $route = $this
      ->router
      ->addRoute('/test', fn () => "test get route", 'test', 'GET')
      ->match($req);

    $this->assertEquals(
      'test get route',
      call_user_func_array($route->getCallback(), [$req])
    );
  }

  public function test_post_route()
  {
    $req = new ServerRequest('POST', '/test');
    $req->getBody()->write('some content');
    $route = $this
      ->router
      ->addRoute('/test', function (ServerRequest $r) {
        return (string)$r->getBody();
      }, 'test', 'POST')
      ->match($req);

    $this->assertEquals('test', $route->getName());
    $this->assertEquals(
      'some content',
      call_user_func_array($route->getCallback(), [$req])
    );
  }

  public function test_route_not_found()
  {
    $req = new ServerRequest('GET', '/not_found');
    $route = $this
      ->router
      ->addRoute('/home', fn () => null, 'home', 'GET')
      ->match($req);

    $this->assertEquals(null, $route);
  }

  public function test_route_with_params()
  {
    $req = new ServerRequest('GET', '/blog/posts/5');
    $route = $this
      ->router
      ->addRoute('/blog/posts/{id}', function (ServerRequest $r) {
        return "post id: {$r->getAttribute('id')}";
      }, 'blog.posts', 'GET')
      ->match($req);

    $req = $req->withAttribute('id', $route->getParams()['id']);

    $this->assertEquals(['id' => 5], $route->getParams());
    $this->assertEquals(
      'post id: 5',
      call_user_func_array($route->getCallback(), [$req])
    );
  }

  public function test_generate_url()
  {
    $req = new ServerRequest('GET', '/blog/mon-slug/5');
    $route = $this
      ->router
      ->addRoute('/blog/{slug}/{id}', fn () => null, 'blog', 'GET')
      ->match($req);

    $this->assertEquals(
      "/blog/mon-slug/5",
      $this->router->generateUrl($route->getName(), $route->getParams())
    );
  }

  public function test_generate_url_with_query_string()
  {
    $req = new ServerRequest('GET', '/blog');
    $route = $this
      ->router
      ->addRoute('/blog', fn () => null, 'blog', 'GET')
      ->match($req);

    $this->assertEquals(
      '/blog?category=surf&page=5',
      $this->router->generateUrl($route->getName(), [], ['category' => 'surf', 'page' => 5])
    );
  }

  public function test_register_route_with_controller_and_method()
  {
    $req = new ServerRequest('GET', '/index');
    $route = $this
      ->router
      ->addRoute('/index', [ControllerFixture::class, 'index'], 'index', 'GET')
      ->match($req);

    [$className, $method] = $route->getCallback();

    $this->assertEquals('Tests\Core\Fixtures\ControllerFixture', $className);

    $this->assertEquals(
      'index page',
      call_user_func_array([new $className(), $method], [$req])
    );
  }

  public function test_register_route_with_controller_attributes()
  {
    $req = new ServerRequest('POST', '/test/create/post');
    $req->getBody()->write(json_encode(
      ['title' => 'some title', 'content' => 'some content']
    ));
    $this
      ->router
      ->registerRoutesFromControllersAttributes([
        TestController::class
      ]);

    $route = $this->router->match($req);

    [$className, $method] = $route->getCallback();

    $this->assertEquals('Tests\Core\Fixtures\ControllerFixture', $className);
    $this->assertEquals('getPost', $method);

    $this->assertEquals(
      'post with title: some title and content: some content created',
      call_user_func_array([new $className(), $method], [$req])
    );
  }
}
