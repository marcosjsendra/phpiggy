<?php

declare(strict_types=1);

namespace Framework;

use ReflectionClass, ReflectionNamedType;
use Framework\Exceptions\ContainerException;

class Container
{
  private array $definitions = [];

  public function addDefinitions(array $newDefinitions)
  {
    // $this->definitions = array_merge($this->definitions, $newDefinitions); //* Solution 1 to merge arrays - PHP Native Function array_merge
    $this->definitions = [...$this->definitions, ...$newDefinitions]; //* Solution 2 to merge arrays - Square brackets with spread operator before each value
  }

  public function resolve(string $className)
  {

    //* Instantiating the Native PHP Class:

    $reflectionClass = new ReflectionClass($className); //? Native PHP class: The ReflectionClass class reports information about a class.
    // https://www.php.net/manual/en/class.reflectionclass.php

    //* Using the isInstantiable Method:

    if (!$reflectionClass->isInstantiable()) { //? Checking if class is instatiable
      throw new ContainerException("Classs {$className} is not instantiable."); //? Throw error if it's not Instatiable.
    }

    //* Getting the __constructor of the class with the getConstructor method

    $constructor = $reflectionClass->getConstructor();

    if (!$constructor) {
      return $className;
    }

    //* Getting the __constructor($param) <- Parameter

    $params = $constructor->getParameters(); //? We are getting the constructor class parameter

    if (count($params) === 0) { //? Checking if the count of param is 0
      return new $className;
    }

    //* Validating the Parameter - using the class ReflectionNamedType:

    $dependencies = []; //? This is going to store the dependecies required for our controller.

    foreach ($params as $param) {
      $name = $param->getName();
      $type = $param->getType();

      if (!$type) {
        throw new ContainerException("Failed to resolve class {$className} because param {$name} is missing a type hint.");
      }
      if (!$type instanceof ReflectionNamedType || $type->isBuiltin()) {
        throw new ContainerException("Failed to resolve class {$className} because invalid param name.");
      }

      $dependencies[] = $this->get($type->getName());
    }

    //* Instantiating the Class with Dependencies:
    // https://www.php.net/manual/en/reflectionclass.newinstanceargs.php
    //? Creates a new instance of the class, the given arguments are passed to the class constructor.

    return $reflectionClass->newInstanceArgs($dependencies);
  }

  //* This method is going to return an instance of any dependency.

  public function get(string $id)
  {
    if (!array_key_exists($id, $this->definitions)) {
      throw new ContainerException("Class {$id} does not exist in container.");
    }
    $factory = $this->definitions[$id];
    $dependency = $factory();

    return $dependency;
  }
}
