<?php

namespace Src\Core\Renderer;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use Src\Core\Interfaces\RendererInterface;

class TwigRenderer implements RendererInterface
{

    public function __construct(private FilesystemLoader $loader, private Environment $twig)
    {
    }

    /**
     * add a Path to views directory using namespace
     *
     * @param mixed $namespace
     * @param mixed $path
     * @return void
     */
    public function addPath(string $namespace, ?string $path = null): void
    {
        $this->loader->addPath($path, $namespace);
    }

    /**
     * render the view with injected params
     *
     * @param mixed $view
     * @param mixed $params
     * @return string
     */
    public function render(string $view, array $params = array()): string
    {
        return $this->twig->render($view . '.twig', $params);
    }

    /**
     * register a global object to use in all views
     *
     * @param mixed $key
     * @param mixed $value
     * @return void
     */
    public function addGlobal(string $key, $value): void
    {
        $this->twig->addGlobal($key, $value);
    }

    public function getTwig()
    {
        return $this->twig;
    }
}
