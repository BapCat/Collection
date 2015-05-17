<?php namespace LordMonoxide\Collection\Traits;

use LordMonoxide\Collection\LazyInitializer;

/**
 * A basic implementation of WritableCollectionInterface
 */
trait WritableCollectionTrait {
  protected $collection = [];
  
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
  
  public function remove($key) {
    unset($this->collection[$key]);
  }
}
