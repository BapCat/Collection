<?php namespace BapCat\Collection\Interfaces;

/**
 * Defines an immutable collection (although the implementation may be mutable)
 */
interface Collection {
  function __new(array $array);
  
  /**
   * Check if the collection contains a key
   * 
   * @param   mixed $key  The key
   * 
   * @returns bool  True if the key exists, false otherwise
   */
  public function has($key);
  
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
  public function get($key, $default = null);
  
  /**
   * Get the first value from the collection
   * 
   * @throws  NoSuchValueException If the collection is empty
   * 
   * @returns mixed The first value from the collection
   */
  public function first();
  
  /**
   * Get the last value from the collection
   * 
   * @throws  NoSuchValueException If the collection is empty
   * 
   * @returns mixed The last value from the collection
   */
  public function last();
  
  /**
   * Get all keys and values from the collection
   * 
   * @returns array An associative array of all keys and
   *                values contained in the collection
   */
  public function all();
  
  /**
   * Iterates over each item in the collection and executes the
   * callback, passing the key and value
   * 
   * @param callable $callback A callback to be called with the key and
   *                           value of each item in the collection.
   *                           `function($key, $value)`
   */
  public function each(callable $callback);
  
  /**
   * Get all keys from the collection
   * 
   * @returns array An array of all keys contained in the collection
   */
  public function keys();
  
  /**
   * Get all values from the collection
   * 
   * @returns array An array of all values contained in the collection
   */
  public function values();
  
  /**
   * Get the size of the collection
   * 
   * @returns int The size of the collection
   */
  public function size();

  /**
   * Get the size of the collection
   * 
   * @returns int The size of the collection
   */
  public function count();
  
  /**
   * Merges two collections together.  If the arrays contain `string` keys, the values
   * from `$other` will take precedence during key collisions
   * 
   * @param   Collection  $other  The collection to merge with this one
   * 
   * @returns Collection  A new collection containing this collection
   *                      with `$other` merged with it
   */
  public function merge(Collection $other);
  
  /**
   * Returns a new collection with only the distinct values
   * 
   * @returns Collection  A new collection containing only the distinct
   *                      values from this collection
   */
  public function distinct();
  
  /**
   * Returns a new collection with the keys and values from this collection reversed
   * 
   * @returns Collection  A new collection containing the keys and values from this
   *                      collection reversed (`[a => b, c => d]` to `[c => d, a => b]`)
   */
  public function reverse();
  
  /**
   * Returns a new collection with the keys and values from this collection swapped
   * 
   * @returns Collection  A new collection containing the keys and values from this
   *                      collection swapped (`[a => b, c => d]` to `[b => a, d => c]`)
   */
  public function flip();
  
  /**
   * Extracts a contiguous section of this collection into a new collection
   * 
   * @returns Collection  A new collection containing a contiguous subset of
   *                      this collection
   */
  public function slice($offset, $length);
  
  /**
   * Removes a contiguous section of this collection and optionally replaces
   * it with the content of `$replacement`
   * 
   * @returns Collection  A new collection containing a contiguous subset of
   *                      this collection
   */
  public function splice($offset, $length, Collection $replacement = null);
  
  /**
   * Filters a collection based on a callback
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
  public function filter(callable $callback);
  
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
  public function map(callable $callback);
}
