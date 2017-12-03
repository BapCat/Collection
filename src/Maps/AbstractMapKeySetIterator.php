<?php declare(strict_types=1); namespace BapCat\Collection\Maps;

use BapCat\Collection\Iterator;
use BapCat\Collection\IteratorDefaultMethods;
use BapCat\Collection\Sets\Set;

class AbstractMapKeySetIterator implements Iterator {
  use IteratorDefaultMethods;

  /**
   * @var  Iterator
   */
  private $i;

  public function __construct(Set $entrySet) {
    $this->i = $entrySet->iterator();
  }

  public function hasNext(): bool {
    return $this->i->hasNext();
  }

  public function next() {
    return $this->i->next()->getKey();
  }

  public function remove(): void {
    $this->i->remove();
  }
}
