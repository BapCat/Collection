<?php namespace BapCat\Collection\Traits;

use BapCat\Collection\Exceptions\NoSuchKeyException;
use BapCat\Collection\Interfaces\Collection;

/**
 * A basic implementation of Collection
 */
trait ReadableCollectionTrait {
  /** {@inheritDoc} */
  public abstract function __new(array $initial);

  /**
   * Check if the collection contains a key
   * 
   * @param  mixed  $key  The key
   * 
   * @return  bool  True if the key exists, false otherwise
   */
  public function has($key) {
    return array_key_exists($key, $this->collection);
  }
  
  /**
   * Search for a value and return its key
   * 
   * @throws  NoSuchKeyException  If `$value` wasn't found
   * 
   * @param  mixed  $value
   * 
   * @return  int|string  The key
   */
  public function search($value) {
    $key = array_search($value, $this->collection, true);
    
    if($key === false) {
      throw new NoSuchKeyException(null, 'No key was found when searching for ' . var_export($value, true));
    }
    
    return $key;
  }
  
  /**
   * Get a value from the collection
   * 
   * @throws  NoSuchKeyException If `$key` refers to a value that does not exist
   * 
   * @param  mixed  $key      The key for which to get a value
   * @param  mixed  $default  (default: null) A default value to return if the collection does not
   *                          contain `$key`.  Prevents NoSuchKeyException from being thrown.
   * 
   * @return  mixed  The value that was bound to `$key`
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
   * @throws  NoSuchKeyException If the collection is empty
   * 
   * @return  mixed  The first value from the collection
   */
  public function first() {
    if($this->size() == 0) {
      throw new NoSuchKeyException(null, "Can't get first value from empty collection");
    }
    
    reset($this->collection);
    $key = key($this->collection);
    
    return $this->get($key);
  }
  
  /**
   * Get the first key and value from the collection
   * 
   * @throws  NoSuchKeyException  If the collection is empty
   * 
   * @return  array  The first key and value from the collection
   */
  public function firstPair() {
    if($this->size() == 0) {
      throw new NoSuchKeyException(null, "Can't get first value from empty collection");
    }
    
    reset($this->collection);
    $key = key($this->collection);
    
    return [$key, $this->get($key)];
  }
  
  /**
   * Get the last value from the collection
   * 
   * @throws  NoSuchKeyException  If the collection is empty
   * 
   * @return  mixed  The last value from the collection
   */
  public function last() {
    if($this->size() == 0) {
      throw new NoSuchKeyException(null, "Can't get last value from empty collection");
    }
    
    end($this->collection);
    $key = key($this->collection);
    
    return $this->get($key);
  }
  
  /**
   * Get the last key and value from the collection
   * 
   * @throws  NoSuchKeyException  If the collection is empty
   * 
   * @return  mixed[]  The last key and value from the collection
   */
  public function lastPair() {
    if($this->size() == 0) {
      throw new NoSuchKeyException(null, "Can't get last value from empty collection");
    }
    
    end($this->collection);
    $key = key($this->collection);
    
    return [$key => $this->get($key)];
  }
  
  /**
   * Get all keys and values from the collection
   * 
   * @deprecated  Use toArray() instead - it's more descriptive of what it does
   * 
   * @return  array  An associative array of all keys and values contained in the collection
   */
  public function all() {
    return $this->toArray();
  }
  
  /**
   * Returns the contents of this collection as an array
   * 
   * @return  array  An associative array of all keys and values contained in the collection
   */
  public function toArray() {
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
   * @param  callable  $callback  `function(int|string $key, mixed $value) : void`
   *                              A callback to be called with the key and value of each item in the collection
   * 
   * @return  void
   */
  public function each(callable $callback) {
    $all = $this->toArray();
    array_walk($all, $callback);
  }
  
  /**
   * Get all keys from the collection
   * 
   * @return  Collection  All keys contained in this collection
   */
  public function keys() {
    return $this->__new(array_keys($this->collection));
  }
  
  /**
   * Get all values from the collection
   * 
   * @return  Collection  All values contained in this collection
   */
  public function values() {
    return $this->__new(array_values($this->toArray()));
  }
  
  /**
   * Get the size of the collection
   * 
   * @return int The size of the collection
   */
  public function size() {
    return count($this->collection);
  }
  
  /**
   * True if the collection is empty
   * 
   * @return  bool
   */
  public function isEmpty() {
    return $this->size() === 0;
  }
  
  /**
   * Merges two collections together.  If the arrays contain `string` keys, the values from `$other`
   * will take precedence during key collisions.
   * 
   * @param  Collection  $other  The collection to merge with this one
   * 
   * @return  Collection  A new collection containing this collection with `$other` merged with it
   */
  public function merge(Collection $other) {
    return $this->__new(array_merge($this->toArray(), $other->toArray()));
  }
  
  /**
   * Returns a new collection with only the distinct values
   * 
   * @return  Collection  A new collection containing only the distinct values from this collection
   */
  public function distinct() {
    return $this->__new(array_unique($this->toArray()));
  }
  
  /**
   * Returns a new collection with the keys and values from this collection reversed
   * 
   * @return  Collection  A new collection containing the keys and values from this collection,
   *                      reversed (`[a => b, c => d]` to `[c => d, a => b]`)
   */
  public function reverse() {
    return $this->__new(array_reverse($this->toArray()));
  }
  
  /**
   * Returns a new collection with the keys and values from this collection swapped
   * 
   * @return  Collection  A new collection containing the keys and values from this collection,
   *                      swapped (`[a => b, c => d]` to `[b => a, d => c]`)
   */
  public function flip() {
    return $this->__new(array_flip($this->toArray()));
  }
  
  /**
   * Extracts a contiguous section of this collection into a new collection
   * 
   * @param  int       $offset  The start of the slice
   * @param  int|null  $length  The length of the slice
   * 
   * @return  Collection  A new collection containing a contiguous subset of this collection
   */
  public function slice($offset, $length = null) {
    return $this->__new(array_slice($this->toArray(), $offset, $length));
  }
  
  /**
   * Removes a contiguous section of this collection and optionally replaces it with the
   * content of `$replacement`
   * 
   * @note  This function <b>will not</b> preserve keys
   * 
   * @param  int              $offset       The start of the splice
   * @param  int              $length       The length of the splice
   * @param  Collection|null  $replacement  (default: null) What to replace the spliced section with
   * 
   * @return  Collection  A new collection containing a contiguous subset of this collection
   */
  public function splice($offset, $length, Collection $replacement = null) {
    $all = $this->toArray();
    
    if($replacement === null) {
      array_splice($all, $offset, $length);
    } else {
      array_splice($all, $offset, $length, $replacement->toArray());
    }
    
    return $this->__new($all);
  }
  
  /**
   * Filters a collection based on a callback
   * 
   * @param  callable  $callback  `function(int|string $key, mixed $value) : bool`
   *                              A callback to be called with the key and value of each item
   *                              in the collection. It should return `true` to include the
   *                              key and value in the new collection, and false otherwise.
   * 
   * @return  Collection  A new collection containing a subset of the values of this collection
   */
  public function filter(callable $callback) {
    return $this->__new(array_filter($this->toArray(), $callback, ARRAY_FILTER_USE_BOTH));
  }
  
  /**
   * Filters a collection based on its keys
   * 
   * @param  callable  $callback  `function(int|string $key): bool`
   *                              A callback to be called with the key of each item in the collection.
   *                              It should return `true` to include the key and value in the new
   *                              collection, and false otherwise.
   * 
   * @return  Collection  A new collection containing a subset of the values of this collection
   */
  public function filterByKeys(callable $callback) {
    return $this->__new(array_filter($this->toArray(), $callback, ARRAY_FILTER_USE_KEY));
  }
  
  /**
   * Filters a collection based on its values
   * 
   * @param  callable  $callback  `function(mixed $value): bool`
   *                              A callback to be called with the value of each item in the collection.
   *                              It should return `true` to include the key and value in the new
   *                              collection, and false otherwise.
   * 
   * @return  Collection  A new collection containing a subset of the values of this collection
   */
  public function filterByValues(callable $callback) {
    return $this->__new(array_filter($this->toArray(), $callback));
  }
  
  /**
   * Creates a new collection based on the values of this collection with a callback applied to them
   * 
   * @note  Mapping keys is not possible with this method
   * 
   * @param  callable  $callback  `function(int|string $key, mixed $value) : mixed`
   *                              A callback to be called with the key and value of each item
   *                              in the collection.  It should return the modified values.
   * 
   * @return  Collection  A new collection containing a modified values.  Keys are maintained.
   */
  public function map(callable $callback) {
    return $this->__new(array_map($callback, $this->toArray()));
  }
  
  /**
   * Creates a new collection with the values of this collection as the values, and the values
   * (<b>not</b> the keys) of `$keys` as the keys.
   * 
   * @param  Collection  $keys  The keys for the new collection
   * 
   * @return  Collection
   */
  public function combine(Collection $keys) {
    return $this->__new(array_combine($keys->toArray(), $this->toArray()));
  }
  
  /**
   * Concatenate all elements of the collection together, using `$glue` as a delimeter
   * 
   * @param  string  $glue  (default: empty string)
   * 
   * @return  string
   */
  public function join($glue = '') {
    return implode($glue, $this->toArray());
  }
}
