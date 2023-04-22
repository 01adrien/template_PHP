<?php

use Clockwork\Support\Vanilla\Clockwork;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Asset\Package;
use Symfony\Component\Asset\Packages;
use Symfony\Component\Asset\VersionStrategy\JsonManifestVersionStrategy;
use Symfony\WebpackEncoreBundle\Asset\EntrypointLookup;
use Symfony\WebpackEncoreBundle\Asset\TagRenderer;
use Src\Core\DataStructures\StackArray;
use Src\Core\Renderer\TwigRendererFactory;
use Src\Core\Router\Router;
use Src\Core\Auth\Auth;
use Src\Core\Config\Config;
use Src\Core\Session\Session;
use Src\Core\Interfaces\AuthInterface;
use Src\Core\Interfaces\CacheInterface;
use Src\Core\Interfaces\ConnectionInterface;
use Src\Core\Interfaces\QueueInterface;
use Src\Core\Interfaces\RendererInterface;
use Src\Core\Interfaces\RouterInterface;
use Src\Core\Interfaces\SessionInterface;
use Src\Core\Interfaces\StorageInterface;
use Src\Core\Middlewares\CsrfMiddleware;
use Src\Core\Middlewares\MiddlewareManager;
use Src\Core\Middlewares\NotFoundHandler;
use Src\Core\Renderer\TwigExtensions\CsrfExtension;
use Src\Core\Renderer\TwigExtensions\RouterExtension;
use Src\Core\Renderer\TwigExtensions\DateExtension;
use Src\Core\Renderer\TwigExtensions\PaginationExtension;
use Src\Core\Enums\SameSite;
use Src\Core\DataObjects\CookiesConfig;
use Src\Core\Log\Factory\LoggerFactory;
use Src\Core\Queue\Factory\QueueFactory;
use Src\Core\Cache\Factory\CacheFactory;
use Src\Core\Database\Factory\ConnectionFactory;
use Src\Core\Storage\Factory\StorageFactory;

return [
  ConnectionInterface::class => \DI\factory(ConnectionFactory::class),
  CacheInterface::class => \DI\factory(CacheFactory::class),
  QueueInterface::class => \DI\factory(QueueFactory::class),
  LoggerInterface::class => \DI\factory(LoggerFactory::class),
  AuthInterface::class => \DI\autowire(Auth::class),
  StorageInterface::class => \DI\factory(StorageFactory::class),
  MiddlewareManager::class => \DI\autowire()->constructor(
      new StackArray(),
      \DI\autowire(NotFoundHandler::class)
  ),
  SessionInterface::class => function (Config $c) {
    return new Session(new CookiesConfig(
        $c->get('cookies.secure'),
        $c->get('cookies.httponly'),
        SameSite::from($c->get('cookies.samesite')),
    ));
  },
  RouterInterface::class => \DI\autowire(Router::class),
  RendererInterface::class => \DI\factory(TwigRendererFactory::class),
  CsrfMiddleware::class => \DI\create()->constructor(\DI\get(SessionInterface::class)),
  'core.twig.extensions' => [
    CsrfExtension::class,
    RouterExtension::class,
    DateExtension::class,
    PaginationExtension::class
  ],
  Clockwork::class => function (): Clockwork {
    return  Clockwork::init([
      'storage_files_path' => STORAGE_DIR . '/clockwork',
      'register_helpers' => true
    ]);
  },
  'webpack_encore.packages' => function () {
    return new Packages(
        new Package(
            new JsonManifestVersionStrategy(BUILD_DIR . '/manifest.json')
        )
    );
  },
  'webpack_encore.tag_renderer' => function (ContainerInterface $c) {
    return new TagRenderer(
        new EntrypointLookup(BUILD_DIR . '/entrypoints.json'),
        $c->get('webpack_encore.packages')
    );
  },
];