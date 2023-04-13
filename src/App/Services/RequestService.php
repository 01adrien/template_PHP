<?php

namespace Src\App\Services;

use Psr\Http\Message\ServerRequestInterface as Request;
use Src\Core\Interfaces\SessionInterface;

class RequestService
{

  public function __construct(private SessionInterface $session)
  {
  }

  public function getRefereUrl(Request $request): string
  {
    // NOT WORKING
    $url = $request->getServerParams()['HTTP_REFERER'] ?? null;
    return parse_url($url)['path'] ?? $this->session->get('previousUrl');;
  }

  public function isXhr(Request $request): bool
  {
    return $request->getHeaderLine('X-Requested-With') === 'XMLHttpRequest';
  }

  public function getCategoryTableParams(Request $request)
  {
    $user = $request->getAttribute('user');
    $search = $request->getQueryParams()['search'] ?? "";
    return [
      'userId' => $user->id,
      'search' => $search
    ];
  }

  public function getTransactionTableParams(Request $request)
  {
    $user = $request->getAttribute('user');
    $search = $request->getQueryParams()['search'] ?? "";
    return [
      'userId' => $user->id,
      'search' => $search
    ];
  }
}
