<?php namespace BapCat\Collection;

use BapCat\Collection\Interfaces\Collection as CollectionInterface;
use BapCat\Collection\Interfaces\WritableCollection as WritableCollectionInterface;
use BapCat\Collection\Traits\CollectionTrait;

use Countable;
use IteratorAggregate;

/**
 * A readable, writable, iterable collection
 */
class Collection implements Countable, CollectionInterface, WritableCollectionInterface, IteratorAggregate {
  use CollectionTrait;
  
  public function __construct(array $initial = []) {
    $this->collection = $initial;
  }
  
  public function __new(array $initial) {
    return new static($initial);
  }
}
