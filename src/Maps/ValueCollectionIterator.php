<?php declare(strict_types=1); namespace BapCat\Collection\Maps;

use BapCat\Collection\Iterator;
use BapCat\Collection\IteratorDefaultMethods;
use BapCat\Collection\Sets\Set;

class ValueCollectionIterator implements Iterator {
  use IteratorDefaultMethods;

  /**
   * @var  Iterator
   */
  private $i;

  /**
   * @param  Set  $entrySet
   */
  public function __construct(Set $entrySet) {
    $this->i = $entrySet->iterator();
  }

  /**
   * @return  bool
   */
  public function hasNext(): bool {
    return $this->i->hasNext();
  }

  /**
   * @return  mixed
   */
  public function next() {
    return $this->i->next()->getValue();
  }

  /**
   * @return  void
   */
  public function remove(): void {
    $this->i->remove();
  }
}
