<?php namespace LordMonoxide\Collection;

/**
 * A basic implementation of WritableCollectionInterface
 */
trait WritableCollectionTrait {
  public function __construct(array $collection = []) {
    $this->collection = $collection;
  }
  
  public function add($value) {
    $this->collection[] = $value;
  }
  
  public function set($key, $value) {
    $this->collection[$key] = $value;
  }
  
  public function lazy($key, callable $initializer) {
    $this->collection[$key] = new LazyInitializer($initializer);
  }
}
