<?php

namespace Tests\Core\Router;

use GuzzleHttp\Psr7\ServerRequest;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Src\Core\Router\Router;
use Tests\Core\Fixtures\TestController;

class TestRouter extends TestCase
{
  private Router $router;

  public function setUp(): void
  {
    $this->router = new Router();
  }
  /** @test */
  public function test_get_route()
  {
    $req = new ServerRequest('GET', '/test');

    $res = $this
      ->router
      ->addRoute('/test', fn () => "test route", 'test', 'GET')
      ->match($req);

    $this->assertEquals(
      'test route',
      call_user_func_array($res->getCallback(), [$req])
    );
  }
}
