<?php namespace LordMonoxide\Collection\Interfaces;

/**
 * A flexible interface for implementing collections
 */
interface ReadableCollectionInterface {
  public function has($key);
  
  /**
   * Get a value from the collection
   * 
   * @throws  NoSuchValueException If `$key` refers to a value that does not exist
   * 
   * @param int   $key      The key for which to get a value
   * @param mixed $default  (optional) A default value to return if one doesn't exist
   * 
   * @returns mixed   The value that was bound to `$key`
   */
  public function get($key, $default = null);
  
  public function first();
  
  public function all();
  
  public function keys();
  
  public function values();
  
  public function size();
}
