<?php namespace BapCat\Collection;

use BapCat\Collection\Interfaces\Collection as CollectionInterface;
use BapCat\Collection\Traits\ReadableCollectionTrait;
use BapCat\Collection\Traits\IterableCollectionTrait;

use Countable;
use IteratorAggregate;

/**
 * A readable, writable, iterable collection
 */
class ReadOnlyCollection implements Countable, CollectionInterface, IteratorAggregate {
  use ReadableCollectionTrait;
  use IterableCollectionTrait;
  
  protected $collection = [];
  protected $lazy = [];
  
  public function __construct(array $initial = []) {
    $this->collection = $initial;
  }
  
  public function __new(array $initial) {
    return new static($initial);
  }
}
