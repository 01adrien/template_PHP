<?php

namespace Src\Core\Router;

/**
 * Class Route
 * Represent a matched route
 */
class Route
{

  public function __construct(
    private string $name,
    private $callback,
    private array $parameters
  ) {
  }

  /**
   * @return string
   */
  public function getName(): string
  {
    return $this->name;
  }

  /**
   * @return callable | string
   */
  public function getCallback()
  {
    return $this->callback;
  }

  /**
   * Retrieve the URL parameters
   * @return string[]
   */
  public function getParams(): array
  {
    return $this->parameters;
  }
}
