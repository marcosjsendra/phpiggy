<?php

declare(strict_types=1);

namespace App\Config;

use Framework\App;
use App\Controllers\{HomeController, AboutController};

function registerRoutes(App $app)
{
  $app->get("/", [HomeController::class, "home"]); //! Magic Class ::class
  $app->get("/about", [AboutController::class, "about"]); // I made a mistake here, I did not added /about in the route.
}
