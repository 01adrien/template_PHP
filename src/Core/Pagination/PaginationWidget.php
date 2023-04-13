<?php

namespace Src\Core\Pagination;

use Src\Core\Interfaces\RendererInterface;

class PaginationWidget
{
  public string $widget = "tailwind";
  public Pagination $pagination;
  public RendererInterface $renderer;

  public function __construct(array $options)
  {
    foreach ($options as $key => $value) {
      $this->$key = $value;
    }
  }

  public function make(): string
  {
    return $this->renderer->render(
      "pagination/$this->widget",
      [
        'pagination'   => $this->pagination,
        'current_page' => $this->pagination->currentPage,
        'page_count'   => $this->pagination->pageCount,
        'per_page'     => $this->pagination->perPage,
        'total_count'  => $this->pagination->totalCount,
      ]
    );
  }
}
