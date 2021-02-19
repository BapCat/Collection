<?php namespace BapCat\Collection\Traits;

use ArrayIterator;

/**
 * Allows collection access via `for` loops
 */
trait IterableCollectionTrait {
  /**
   * {@inheritDoc}
   */
  abstract function toArray();

  /**
   * Gets the iterator necessary for `for` loops
   * 
   * @return ArrayIterator Necessary for iteration
   */
  public function getIterator() {
    return new ArrayIterator($this->toArray());
  }
}
