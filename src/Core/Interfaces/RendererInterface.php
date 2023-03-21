<?php

namespace Src\Core\Interfaces;

interface RendererInterface
{
  /**
   * add a Path to views directory using namespace
   *
   * @param  mixed $namespace
   * @param  mixed $path
   * @return void
   */
  public function addPath(string $namespace, ?string $path = null): void;

  /**
   * render the view with injected params
   *
   * @param  mixed $view
   * @param  mixed $params
   * @return string
   */
  public function render(string $view, array $params = []): string;

  /**
   * register a global object to use in all views
   *
   * @param  mixed $key
   * @param  mixed $value
   * @return void
   */
  public function addGlobal(string $key, $value): void;
}
