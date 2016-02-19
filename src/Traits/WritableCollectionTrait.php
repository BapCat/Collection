<?php namespace BapCat\Collection\Traits;

use BapCat\Collection\Exceptions\NoSuchKeyException;

/**
 * A basic implementation of WritableCollectionInterface
 */
trait WritableCollectionTrait {
  /**
   * @var array $collection Holds all key/value pairs in the collection
   */
  protected $collection = [];
  
  /**
   * Holds all lazy-loaded keys
   * 
   * @var  array<mixed>
   */
  protected $lazy = [];
  
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
    $this->collection[$key] = $initializer;
    $this->lazy[$key] = $key;
  }
  
  /**
   * {@inheritdoc}
   */
  public function take($key, $default = null) {
    if(!array_key_exists($key, $this->collection)) {
      if(func_num_args() < 2) {
        throw new NoSuchKeyException($key);
      } else {
        return $default;
      }
    }
    
    $value = $this->collection[$key];
    $this->remove($key);
    
    if(array_key_exists($key, $this->lazy)) {
      $value = $value($key);
      unset($this->lazy[$key]);
    }
    
    return $value;
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
