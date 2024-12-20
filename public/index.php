<?php

declare(strict_types=1);

// echo "<pre>";
// print_r($_SERVER);
// echo "</pre>";

include __DIR__ . "/../src/App/functions.php";

$app = include __DIR__ . "/../src/App/bootstrap.php";

$app->Run();
