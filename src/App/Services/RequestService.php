<?php

namespace Src\App\Services;

use Psr\Http\Message\ServerRequestInterface as Request;
use Src\Core\Interfaces\SessionInterface;

class RequestService
{

    public function __construct(private SessionInterface $session)
    {
    }

    public function isXhr(Request $request): bool
    {
        return $request->getHeaderLine('X-Requested-With') === 'XMLHttpRequest';
    }
}