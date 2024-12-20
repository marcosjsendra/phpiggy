<?php

declare(strict_types=1);

namespace Framework;

class TemplateEngine
{
  public function __construct(private string $basePath) {}

  public function render(string $template, array $data = [])
  {
    extract($data, EXTR_SKIP);

    ob_start(); //! this a default php function that allows you to store the output of a function in a variable.

    include $this->resolve($template);

    $output = ob_get_contents();

    ob_end_clean(); //! Ends and cleans buffer

    return $output;
  }
  public function resolve(string $path)
  {
    return "{$this->basePath}/{$path}";
  }
}
