<?php

declare(strict_types=1);

require __DIR__ . "/../../vendor/autoload.php"; //! use __DIR__ to create an Absolute Path

use \Framework\App;
use App\Config\Paths;

use function App\Config\{registerRoutes, registerMiddleware};

$app = new App(Paths::SOURCE . "App/container-definitions.php");

registerRoutes($app);
registerMiddleware($app);

return $app;
