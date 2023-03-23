<?php

use Psr\Container\ContainerInterface;
use Src\Core\Database\DatabaseFactory;
use Src\Core\DataStructures\StackArray;
use Src\Core\Interfaces\RendererInterface;
use Src\Core\Middlewares\{
  MiddlewareManager,
  NotFoundHandler
};


use Src\Core\Renderer\TwigRendererFactory;
use Symfony\Component\Asset\Package;
use Symfony\Component\Asset\Packages;
use Symfony\Component\Asset\VersionStrategy\JsonManifestVersionStrategy;
use Symfony\WebpackEncoreBundle\Asset\EntrypointLookup;
use Symfony\WebpackEncoreBundle\Asset\TagRenderer;

return [
  'db.name' => $_ENV['DB_NAME'],
  'db.host' => $_ENV['DB_HOST'],
  'db.pass' => $_ENV['DB_PASS'],
  'db.user' => $_ENV['DB_USER'],
  MiddlewareManager::class => \DI\create()->constructor(
    new StackArray(),
    new NotFoundHandler()
  ),
  RendererInterface::class => \DI\factory(TwigRendererFactory::class),
  \PDO::class => \DI\factory(DatabaseFactory::class),
  'webpack_encore.packages'     => fn () => new Packages(
    new Package(new JsonManifestVersionStrategy(BUILD_DIR . '/manifest.json'))
  ),
  'webpack_encore.tag_renderer' => fn (ContainerInterface $container) => new TagRenderer(
    new EntrypointLookup(BUILD_DIR . '/entrypoints.json'),
    $container->get('webpack_encore.packages')
  ),
];
