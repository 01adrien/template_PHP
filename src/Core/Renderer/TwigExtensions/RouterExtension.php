<?php

namespace Src\Core\Renderer\TwigExtensions;

use Src\Core\Router\Router;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class RouterExtension extends AbstractExtension
{
  public function __construct(private Router $router)
  {
  }
  public function getFunctions()
  {

    return [
      new TwigFunction('is_active', [$this, 'isActive'], ['is_safe' => ['html']])
    ];
  }

  public function isActive(string $path): string
  {
    return $path === strtok($_SERVER['REQUEST_URI'], '?') ? ' text-blue-400' : ' text-gray-700';
  }
}
