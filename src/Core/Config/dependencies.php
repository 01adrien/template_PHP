<?php

use Clockwork\Support\Vanilla\Clockwork;
use Psr\Container\ContainerInterface;
use Symfony\{
  Component\Asset\Package,
  Component\Asset\Packages,
  Component\Asset\VersionStrategy\JsonManifestVersionStrategy,
  WebpackEncoreBundle\Asset\EntrypointLookup,
  WebpackEncoreBundle\Asset\TagRenderer
};
use Src\Core\{
  Database\DatabaseFactory,
  DataStructures\StackArray,
  Renderer\TwigRendererFactory,
  Router\Router,
  Auth\Auth,
  Config\Config,
  Session\Session
};
use Src\Core\Interfaces\{
  AuthInterface,
  RendererInterface,
  RouterInterface,
  SessionInterface,
};
use Src\Core\Middlewares\{
  CsrfMiddleware,
  MiddlewareManager,
  NotFoundHandler,
};
use Src\Core\Enums\{SameSite};
use Src\Core\DataObjects\{CookiesConfig};
use Src\Core\Renderer\TwigExtensions\{
  CsrfExtension,
  RouterExtension,
  DateExtension,
  PaginationExtension
};

return [
  \PDO::class => \DI\factory(DatabaseFactory::class),
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
  AuthInterface::class => \DI\autowire(Auth::class),
  MiddlewareManager::class => \DI\autowire()->constructor(
    new StackArray(),
    \DI\autowire(NotFoundHandler::class)
  ),
  SessionInterface::class => function (Config $c) {
    return new Session(new CookiesConfig(
      $c->get('cookies.secure'),
      $c->get('cookies.httpOnly'),
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
  Clockwork::class => function () {
    return  Clockwork::init([
      'storage_files_path' => STORAGE_DIR . '/clockwork',
      'register_helpers' => true
    ]);
  }
];
