<?php

declare(strict_types=1);

namespace Framework;

use Framework\Contracts\MiddlewareInterface;

class router
{
  private array $routers = [];
  private array $middlewares = [];

  public function add(string $method, string $path, array $controller)
  {
    $path = $this->normalizePath($path);
    $this->routers[] = [
      "path" => $path,
      "method" => strtoupper($method),
      "controller" => $controller
    ];
  }

  private function normalizePath(string $path): string
  {
    $path = trim($path, "/");
    $path = "/{$path}/";
    $path = preg_replace("#[/]{2,}#", "/", $path);

    return $path;
  }


  function dispatch(string $path, string $method, Container $container = null) //? Initially the container would be null because it is optional to increase scalability with other frameworks
  {
    $path = $this->normalizePath($path);
    $method = strtoupper($method);

    foreach ($this->routers as $route) {
      if (!preg_match("#^{$route['path']}$#", $path) || $route["method"] !== $method) {
        continue;
      }

      [$class, $function] = $route["controller"];

      // $controllerInstance = $container ? $container->resolve($class) : new $class; //? This a Ternary operator which is the same as:
      //? This:
      if ($container) {
        $controllerInstance = $container->resolve($class);
      } else {
        $controllerInstance = new $class;
      };

      $action = fn() => $controllerInstance->{$function}();

      foreach ($this->middlewares as $middleware) {
        $middlewareInstance = $container ? $container->resolve($middleware) : new $middleware;
        $action = fn() => $middlewareInstance->process($action);
      }

      $action();

      return;
    }
  }

  public function addMiddleware(string $middleware)
  {
    $this->middlewares[] = $middleware;
  }
}
