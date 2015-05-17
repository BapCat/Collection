<?php namespace LordMonoxide\Collection\Traits;

use ArrayIterator;

trait IterableCollectionTrait {
  public function getIterator() {
    return new ArrayIterator($this->collection);
  }
}
