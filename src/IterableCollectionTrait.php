<?php namespace LordMonoxide\Collection;

use ArrayIterator;

trait IterableCollectionTrait {
  public function getIterator() {
    return new ArrayIterator($this->collection);
  }
}
