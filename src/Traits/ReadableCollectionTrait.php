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
    // If the key doesn't exist...
    if(!$this->has($key)) {
      // No default?
      if(func_num_args() < 2) {
        // Throw exception if the key doesn't exist
        throw new NoSuchKeyException($key);
      } else {
        // Otherwise, return the default
        return $default;
      }
    }
    
    // Grab the value
    $value = $this->collection[$key];
    
    // Is it a lazy-loaded value
    if($value instanceof LazyInitializer) {
      // Evaluate and cache the result
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
