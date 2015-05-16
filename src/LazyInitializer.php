<?php namespace LordMonoxide\Collection;

class LazyInitializer {
  private $initializer = null;
  
  public function __construct(callable $initializer) {
    $this->initializer = $initializer;
  }
  
  public function __invoke($key) {
    $initializer = $this->initializer;
    return $initializer($key);
  }
}