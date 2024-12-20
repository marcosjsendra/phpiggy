<?php

declare(strict_types=1);

namespace App\Controllers;

use Framework\TemplateEngine;
use App\Config\Paths;


class AboutController
{
  //* Old method:

  // private TemplateEngine $view;

  // public function __construct()
  // {
  //   $this->view = new TemplateEngine(Paths::VIEW);
  // }

  //* New Method

  public function __construct(private TemplateEngine $view) {}

  public function about()
  {
    echo $this->view->render("/about.php", [
      "title" => "About us",
      "dangerousData" => "<script>alert(123)</script>"
    ]);
  }
}
