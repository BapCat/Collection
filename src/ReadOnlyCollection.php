<?php namespace BapCat\Collection;

use BapCat\Collection\Interfaces\Collection as CollectionInterface;
use BapCat\Collection\Traits\ReadableCollectionTrait;
use BapCat\Collection\Traits\IterableCollectionTrait;

use IteratorAggregate;

/**
 * A readable, writable, iterable collection
 */
class ReadOnlyCollection implements CollectionInterface, IteratorAggregate {
  use ReadableCollectionTrait;
  use IterableCollectionTrait;
  
  public function __construct(array $initial = []) {
    $this->collection = $initial;
  }
  
  public function __new(array $initial) {
    return new static($initial);
  }
}
