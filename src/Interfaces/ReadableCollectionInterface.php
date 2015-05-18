<?php namespace LordMonoxide\Collection\Interfaces;

/**
 * Defines a collection that may be read from
 */
interface ReadableCollectionInterface {
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
   * Get all keys and values from the collection
   * 
   * @returns array An associative array of all keys and
   *                values contained in the collection.
   */
  public function all();
  
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
}
