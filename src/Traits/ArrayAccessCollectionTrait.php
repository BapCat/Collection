<?php namespace BapCat\Collection\Traits;

/**
 * Allows collection access via array access
 */
trait ArrayAccessCollectionTrait {
  /** {@inheritDoc} */
  public abstract function has($key);

  /** {@inheritDoc} */
  public abstract function get($key);

  /** {@inheritDoc} */
  public abstract function add($value);

  /** {@inheritDoc} */
  public abstract function set($key, $value);

  /** {@inheritDoc} */
  public abstract function remove($key);

  /**
   * Sets a value in the collection
   * 
   * @param mixed $offset The key
   * @param mixed $value  The value
   *
   * @return void
   */
  public function offsetSet(mixed $offset, mixed $value): void {
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
   *
   * @return bool
   */
  public function offsetExists(mixed $offset): bool {
    return $this->has($offset);
  }
  
  /**
   * Removes a key from the collection
   * 
   * @param mixed $offset The key
   *
   * @return void
   */
  public function offsetUnset(mixed $offset): void {
    $this->remove($offset);
  }
  
  /**
   * Gets a value from the collection
   * 
   * @param mixed $offset The key
   *
   * @return mixed
   */
  public function offsetGet(mixed $offset): mixed {
    return $this->get($offset);
  }
}
