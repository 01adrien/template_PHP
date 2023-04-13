<?php

namespace Src\Core\Pagination;


class Pagination
{
  public int $currentPage = 1;
  public int $offset;
  public int $limit;
  public int $perPage = 10;
  public int $totalCount;
  public int $pageCount;
  public string $basePath;
  public array $queryParams;

  public function __construct(array $options)
  {
    foreach ($options as $key => $value) {
      $this->$key = $value;
    }
    $this->init();
  }


  public function setCurrentPage(int $page): self
  {
    $this->currentPage = $page;
    $this->init();
    return $this;
  }

  public function setPerPage(int $number): self
  {
    $this->perPage = $number;
    $this->init();
    return $this;
  }

  public function createUrl(int $page): string
  {
    $this->queryParams['page'] = $page;
    return $this->basePath . '?' . http_build_query($this->queryParams);
  }

  public function init()
  {
    $this->pageCount = ceil($this->totalCount / $this->perPage);
    $this->offset = $this->perPage * ($this->currentPage - 1);
    $this->limit = $this->perPage;
  }
}
