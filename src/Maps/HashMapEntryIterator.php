<?php declare(strict_types=1); namespace BapCat\Collection\Maps;

use BapCat\Collection\Iterator;

class HashMapEntryIterator extends HashMapHashIterator implements Iterator {
  public function next() { return $this->nextNode(); }
}
