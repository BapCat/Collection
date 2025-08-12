<?php namespace BapCat\Collection\Traits;

use ArrayIterator;
use Traversable;

/**
 * Allows collection access via `for` loops
 */
trait IterableCollectionTrait {
  /**
   * {@inheritDoc}
   */
  public abstract function toArray();

  /**
   * Gets the iterator necessary for `for` loops
   * 
   * @return Traversable Necessary for iteration
   */
  public function getIterator(): Traversable {
    return new ArrayIterator($this->toArray());
  }
}
