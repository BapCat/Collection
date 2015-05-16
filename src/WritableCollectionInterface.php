<?php namespace LordMonoxide\Collection;

/**
 * A flexible interface for implementing collections
 */
interface WritableCollectionInterface {
  public function add($value);
  
  public function set($key, $value);
  
  public function lazy($key, callable $initializer);
}
