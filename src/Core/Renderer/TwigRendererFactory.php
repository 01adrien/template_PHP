<?php

namespace Src\Core\Renderer;

use Pagerfanta\Twig\Extension\PagerfantaExtension;
use Psr\Container\ContainerInterface;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use Twig\RuntimeLoader\ContainerRuntimeLoader;

class TwigRendererFactory
{

    public function __invoke(): TwigRenderer
    {
        $loader = new FilesystemLoader(VIEWS_DIR);
        $twig = new Environment($loader, [
            'debug' => true,
            'cache' => false,
        ]);
        /*
        $twig->addRuntimeLoader(new ContainerRuntimeLoader($container));
        if ($container->has('twig.extensions')) {
            foreach ($container->get('twig.extensions') as $extension) {
                $twig->addExtension($extension);
            }
        }
        */
        return new TwigRenderer($loader, $twig);
    }
}
