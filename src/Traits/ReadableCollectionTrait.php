<?php namespace BapCat\Collection\Traits;

use BapCat\Collection\Exceptions\NoSuchKeyException;
use BapCat\Collection\Interfaces\Collection;

/**
 * A basic implementation of Collection
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
    
    $value = $this->collection[$key];
    
    if(array_key_exists($key, $this->lazy)) {
      $value = $value($key);
      $this->collection[$key] = $value;
      unset($this->lazy[$key]);
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
    
    reset($this->collection);
    $key = key($this->collection);
    
    return $this->get($key);
  }
  
  /**
   * Get the last value from the collection
   * 
   * @throws  NoSuchValueException If the collection is empty
   * 
   * @returns mixed The last value from the collection
   */
  public function last() {
    if($this->size() == 0) {
      throw new NoSuchKeyException(null, 'Can\'t get last value from empty collection');
    }
    
    end($this->collection);
    $key = key($this->collection);
    
    return $this->get($key);
  }
  
  /**
   * Get all keys and values from the collection
   * 
   * @returns array An associative array of all keys and
   *                values contained in the collection.
   */
  public function all() {
    if(!empty($this->lazy)) {
      foreach($this->lazy as $key) {
        $this->collection[$key] = $this->collection[$key]($key);
      }
      
      $this->lazy = [];
    }
    
    return $this->collection;
  }
  
  /**
   * Iterates over each item in the collection and executes the
   * callback, passing the key and value
   * 
   * @param callable $callback A callback to be called with the key and
   *                           value of each item in the collection.
   *                           `function($key, $value)`
   */
  public function each(callable $callback) {
    $all = $this->all();
    array_walk($all, $callback);
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
    return array_values($this->all());
  }
  
  /**
   * Get the size of the collection
   * 
   * @returns int The size of the collection
   */
  public function size() {
    return count($this->collection);
  }
  
  /**
   * Merges two collections together.  If the arrays contain `string` keys, the values
   * from `$other` will take precedence during key collisions
   * 
   * @param   Collection  $other  The collection to merge with this one
   * 
   * @returns Collection  A new collection containing this collection
   *                      with `$other` merged with it
   */
  public function merge(Collection $other) {
    return $this->__new(array_merge($this->all(), $other->all()));
  }
  
  /**
   * Returns a new collection with only the distinct values
   * 
   * @returns Collection  A new collection containing only the distinct
   *                      values from this collection
   */
  public function distinct() {
    return $this->__new(array_unique($this->all()));
  }
  
  /**
   * Returns a new collection with the keys and values from this collection reversed
   * 
   * @returns Collection  A new collection containing the keys and values from this
   *                      collection reversed (`[a => b, c => d]` to `[c => d, a => b]`)
   */
  public function reverse() {
    return $this->__new(array_reverse($this->all()));
  }
  
  /**
   * Returns a new collection with the keys and values from this collection swapped
   * 
   * @returns Collection  A new collection containing the keys and values from this
   *                      collection swapped (`[a => b, c => d]` to `[b => a, d => c]`)
   */
  public function flip() {
    return $this->__new(array_flip($this->all()));
  }
  
  /**
   * Extracts a contiguous section of this collection into a new collection
   * 
   * @returns Collection  A new collection containing a contiguous subset of
   *                      this collection
   */
  public function slice($offset, $length = null) {
    return $this->__new(array_slice($this->all(), $offset, $length));
  }
  
  /**
   * Removes a contiguous section of this collection and optionally replaces
   * it with the content of `$replacement`
   * 
   * @note This function <b>will not</b> preserve keys
   * 
   * @returns Collection  A new collection containing a contiguous subset of
   *                      this collection
   */
  public function splice($offset, $length, Collection $replacement = null) {
    $all = $this->all();
    
    if($replacement === null) {
      array_splice($all, $offset, $length);
    } else {
      array_splice($all, $offset, $length, $replacement ? $replacement->all() : null);
    }
    
    return $this->__new($all);
  }
  
  /**
   * Filters a collection based on its keys and values
   * 
   * @param   callable    $callback A callback to be called with the key and
   *                                value of each item in the collection.
   *                                `function($key, $value)`
   *                                The callback should return `true` to include
   *                                the key and value in the new collection, and
   *                                false otherwise
   * 
   * @returns Collection  A new collection containing a subset of the values of
   *                      this collection
   */
  public function filter(callable $callback) {
    return $this->__new(array_filter($this->all(), $callback, ARRAY_FILTER_USE_BOTH));
  }
  
  /**
   * Filters a collection based on its keys
   * 
   * @param   callable    $callback A callback to be called with the key and
   *                                value of each item in the collection.
   *                                `function($key)`
   *                                The callback should return `true` to include
   *                                the key and value in the new collection, and
   *                                false otherwise
   * 
   * @returns Collection  A new collection containing a subset of the values of
   *                      this collection
   */
  public function filterByKeys(callable $callback) {
    return $this->__new(array_filter($this->all(), $callback, ARRAY_FILTER_USE_KEY));
  }
  
  /**
   * Filters a collection based on its values
   * 
   * @param   callable    $callback A callback to be called with the key and
   *                                value of each item in the collection.
   *                                `function($value)`
   *                                The callback should return `true` to include
   *                                the key and value in the new collection, and
   *                                false otherwise
   * 
   * @returns Collection  A new collection containing a subset of the values of
   *                      this collection
   */
  public function filterByValues(callable $callback) {
    return $this->__new(array_filter($this->all(), $callback));
  }
  
  /**
   * Creates a new collection based on the values of this collection with a
   * callback applied to them
   * 
   * @param   callable    $callback A callback to be called with the key and
   *                                value of each item in the collection.
   *                                `function($key, $value)`
   *                                The callback should return the value, or
   *                                a modified version of it
   * 
   * @returns Collection  A new collection containing a modified version of
   *                      this collection
   */
  public function map(callable $callback) {
    return $this->__new(array_map($callback, $this->all()));
  }
}
