<?php

namespace Src\Core\Renderer\TwigExtensions;

use Src\Core\Pagination\Pagination;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class PaginationExtension extends AbstractExtension
{

  public function getFunctions()
  {

    return [
      new TwigFunction('pagination_widget', [$this, 'paginationWidget'], ['is_safe' => ['html']]),
      new TwigFunction('pagination_label', [$this, 'paginationLabel'], ['is_safe' => ['html']]),
      new TwigFunction('pagination_button', [$this, 'paginationButton'], ['is_safe' => ['html']]),
    ];
  }

  public function paginationWidget(Pagination $pagination, $page): string
  {
    $active = "bg-white";
    if ($pagination->currentPage == $page) $active = "bg-gray-200 text-gray-900";
    $linkToPage =  $pagination->createUrl($page);
    return "
    <li>
      <a href='$linkToPage' class='pagination $active'>$page</a>
    </li>";
  }

  public function paginationLabel(Pagination $pagination): string
  {
    extract(get_object_vars($pagination));

    $from = '0';
    $to = '0';
    $of = '0';

    $class = 'font-semibold text-gray-900';

    if ($totalCount) {
      $from = $currentPage * $perPage - $perPage + 1;
      $to = $currentPage === $pageCount ? $totalCount : $currentPage * $perPage;
      $of = $totalCount;
    }

    return "
    <span class='text-xs text-gray-700 mb-4'>
      Showing 
      <span class=$class>$from</span>
      to <span class=$class>$to</span> 
      of <span class=$class>$of</span> Entries
    </span>
    ";
  }

  public function paginationButton(string $type, Pagination $pagination)
  {
    extract(get_object_vars($pagination));

    $prevUrl = $currentPage > 1 ? $pagination->createUrl($currentPage - 1) : '#';
    $nextUrl = $currentPage < $pageCount ? $pagination->createUrl($currentPage + 1) : '#';

    return match ($type) {
      'prev' => "<li>
                  <a href=$prevUrl class='pagination ml-0 rounded-l-lg'>Prev</a>
                 </li>",
      'next' => "<li>
                  <a href=$nextUrl class='pagination ml-0 rounded-r-lg'>Next</a>
                 </li>",
    };
  }
}
