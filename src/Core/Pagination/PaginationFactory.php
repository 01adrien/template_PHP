<?php

namespace Src\Core\Pagination;

use Psr\Http\Message\ServerRequestInterface as Request;

class PaginationFactory
{

  public static ?Pagination $pagination = null;
  public static ?PaginationWidget $widget = null;



  public static function paginate(Request $request, array $paginationConfig): Pagination
  {
    if (is_null(self::$pagination)) {
      self::$pagination = new Pagination(array_merge(
        $paginationConfig,
        [
          'basePath' => $request->getUri()->getPath(),
          'queryParams' => $request->getQueryParams()
        ]
      ));
    }
    $currentPage = $request->getQueryParams()['page'] ?? 1;
    self::$pagination->setCurrentPage($currentPage);
    return self::$pagination;
  }

  public static function makeWidget(array $widgetConfig): PaginationWidget
  {
    self::$widget = new PaginationWidget(array_merge(
      $widgetConfig,
      ['pagination' => self::$pagination]
    ));

    return self::$widget;
  }
}
