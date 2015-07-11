<?php namespace BapCat\Collection;

use BapCat\Collection\Interfaces\Collection;
use BapCat\Collection\Interfaces\WritableCollection;
use BapCat\Collection\Traits\ArrayAccessCollectionTrait;
use BapCat\Collection\Traits\CollectionTrait;

use ArrayAccess;
use IteratorAggregate;

/**
 * A readable, writable, iterable, array-accessible collection
 */
class ArrayAccessCollection implements Collection, WritableCollection, IteratorAggregate, ArrayAccess {
  use CollectionTrait, ArrayAccessCollectionTrait;
  
  public function __construct(array $initial = []) {
    $this->collection = $initial;
  }
  
  public function __new(array $initial) {
    return new static($initial);
  }
}
