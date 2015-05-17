<?php namespace LordMonoxide\Collection\Traits;

use LordMonoxide\Collection\Exceptions\NoSuchKeyException;
use LordMonoxide\Collection\LazyInitializer;

/**
 * A basic implementation of ReadableCollectionInterface
 */
trait ReadableCollectionTrait {
  public function has($key) {
    return array_key_exists($key, $this->collection);
  }
  
  /**
   * Get a value from the collection
   * 
   * @throws  NoSuchKeyException If `$key` refers to a value that does not exist
   * 
   * @param int   $key      The key for which to get a value
   * @param mixed $default  (optional) A default value to return if one doesn't exist
   * 
   * @returns mixed   The value that was bound to `$key`
   */
  public function get($key, $default = null) {
    if(!$this->has($key)) {
      if(func_num_args() < 2) {
        throw new NoSuchKeyException($key);
      } else {
        return $default;
      }
    }
    
    $value = $this->collection[$key];
    
    if($value instanceof LazyInitializer) {
      $value = $value($key);
      $this->collection[$key] = $value;
    }
    
    return $value;
  }
  
  public function first() {
    if($this->size() == 0) {
      throw new NoSuchKeyException(null, 'Can\'t get first value from empty collection');
    }
    
    return reset($this->collection);
  }
  
  public function all() {
    return $this->collection;
  }
  
  public function keys() {
    return array_keys($this->collection);
  }
  
  public function values() {
    return array_values($this->collection);
  }
  
  public function size() {
    return count($this->collection);
  }
}
