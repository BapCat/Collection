<?php namespace LordMonoxide\Collection\Traits;

use LordMonoxide\Collection\LazyInitializer;

/**
 * A basic implementation of WritableCollectionInterface
 */
trait WritableCollectionTrait {
  /**
   * @var array $collection Holds all key/value pairs in the collection
   */
  protected $collection = [];
  
  /**
   * Constructs a new WritableCollectionTrait
   * 
   * @param array $collection (optional) An associative array of initial
   *                          values for this collection
   */
  public function __construct(array $collection = []) {
    $this->collection = $collection;
  }
  
  /**
   * Add a value to the collection
   * 
   * @param mixed $value  The value
   */
  public function add($value) {
    $this->collection[] = $value;
  }
  
  /**
   * Add a keyed value to the collection
   * 
   * @param mixed $key    The key
   * @param mixed $value  The value
   */
  public function set($key, $value) {
    $this->collection[$key] = $value;
  }
  
  /**
   * Add a keyed, lazy-loaded value to the collection. `$initializer`
   * will not be executed until the value is requested for the first
   * time, at which point it will be replaced with the resultant value.
   * 
   * @param mixed     $key          The key
   * @param callable  $initializer  A callable that will be evaluated to
   *                                the desired value upon first request.
   */
  public function lazy($key, callable $initializer) {
    $this->collection[$key] = new LazyInitializer($initializer);
  }
  
  /**
   * Removes a key from the array
   * 
   * @param mixed $key    The key to remove
   */
  public function remove($key) {
    unset($this->collection[$key]);
  }
}
