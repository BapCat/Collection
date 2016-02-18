<?php namespace BapCat\Collection\Interfaces;

/**
 * Defines a collection that may be written to
 */
interface WritableCollection {
  /**
   * Add a value to the collection
   * 
   * @param mixed $value  The value
   */
  public function add($value);
  
  /**
   * Add a keyed value to the collection
   * 
   * @param mixed $key    The key
   * @param mixed $value  The value
   */
  public function set($key, $value);
  
  /**
   * Add a keyed, lazy-loaded value to the collection. `$initializer`
   * will not be executed until the value is requested for the first
   * time, at which point it will be replaced with the resultant value.
   * 
   * @param mixed     $key          The key
   * @param callable  $initializer  A callable that will be evaluated to
   *                                the desired value upon first request.
   */
  public function lazy($key, callable $initializer);
  
  /**
   * Removes a key from the array
   * 
   * @param mixed $key    The key to remove
   */
  public function remove($key);
  
  /**
   * Clears the collection
   */
  public function clear();
}
