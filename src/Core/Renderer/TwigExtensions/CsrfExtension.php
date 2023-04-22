<?php

namespace Src\Core\Renderer\TwigExtensions;

use Src\Core\Middlewares\CsrfMiddleware;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class CsrfExtension extends AbstractExtension
{


    public function __construct(private CsrfMiddleware $csrfMiddleware)
    {
        $this->csrfMiddleware = $csrfMiddleware;
    }

    public function getFunctions()
    {
        return [
        new TwigFunction('csrf_input', [$this, 'csrfInput'], ['is_safe' => ['html']])
        ];
    }

    public function csrfInput(): string
    {
        return '<input class=csrf type="hidden" ' .
        'name="' . $this->csrfMiddleware->getFormKey() . '" ' .
        'value="' . $this->csrfMiddleware->generateToken() . '"/>';
    }
}