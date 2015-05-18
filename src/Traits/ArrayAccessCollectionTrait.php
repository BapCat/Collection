<?php namespace LordMonoxide\Collection\Traits;

/**
 * Allows collection access via array access
 */
trait ArrayAccessCollectionTrait {
  /**
   * Sets a value in the collection
   * 
   * @param mixed $offset (optional) The key
   * @param mixed $value  The value
   */
  public function offsetSet($offset = null, $value) {
    if(is_null($offset)) {
      $this->add($value);
    } else {
      $this->set($offset, $value);
    }
  }
  
  /**
   * Checks if a key exists
   * 
   * @param mixed $offset The key
   */
  public function offsetExists($offset) {
    return $this->has($offset);
  }
  
  /**
   * Removes a key from the collection
   * 
   * @param mixed $offset The key
   */
  public function offsetUnset($offset) {
    $this->remove($offset);
  }
  
  /**
   * Gets a value from the collection
   * 
   * @param mixed $offset The key
   */
  public function offsetGet($offset) {
    return $this->get($offset);
  }
}
