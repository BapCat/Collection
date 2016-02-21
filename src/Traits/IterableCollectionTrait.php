<?php namespace BapCat\Collection\Traits;

use ArrayIterator;

/**
 * Allows collection access via `for` loops
 */
trait IterableCollectionTrait {
  /**
   * Gets the iterator necessary for `for` loops
   * 
   * @returns ArrayIterator Necessary for iteration
   */
  public function getIterator() {
    return new ArrayIterator($this->all());
  }
}
