<?php namespace LordMonoxide\Collection\Traits;

use LordMonoxide\Collection\Exceptions\NoSuchKeyException;
use LordMonoxide\Collection\LazyInitializer;

/**
 * A basic implementation of ReadableCollectionInterface
 */
trait ReadableCollectionTrait {
  /**
   * Check if the collection contains a key
   * 
   * @param   mixed $key  The key
   * 
   * @returns bool  True if the key exists, false otherwise
   */
  public function has($key) {
    return array_key_exists($key, $this->collection);
  }
  
  /**
   * Get a value from the collection
   * 
   * @throws  NoSuchValueException If `$key` refers to a value that does not exist
   * 
   * @param mixed $key      The key for which to get a value
   * @param mixed $default  (optional) A default value to return if the collection does not
   *                        contain `$key`.  Prevents NoSuchValueException from being thrown.
   * 
   * @returns mixed The value that was bound to `$key`
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
  
  /**
   * Get the first value from the collection
   * 
   * @throws  NoSuchValueException If the collection is empty
   * 
   * @returns mixed The first value from the collection
   */
  public function first() {
    if($this->size() == 0) {
      throw new NoSuchKeyException(null, 'Can\'t get first value from empty collection');
    }
    
    return reset($this->collection);
  }
  
  /**
   * Get all keys and values from the collection
   * 
   * @returns array An associative array of all keys and
   *                values contained in the collection.
   */
  public function all() {
    return $this->collection;
  }
  
  /**
   * Get all keys from the collection
   * 
   * @returns array An array of all keys contained in the collection
   */
  public function keys() {
    return array_keys($this->collection);
  }
  
  /**
   * Get all values from the collection
   * 
   * @returns array An array of all values contained in the collection
   */
  public function values() {
    return array_values($this->collection);
  }
  
  /**
   * Get the size of the collection
   * 
   * @returns int The size of the collection
   */
  public function size() {
    return count($this->collection);
  }
}
