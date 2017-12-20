<?php declare(strict_types=1); namespace BapCat\Collection\Maps;

use BapCat\Collection\IllegalStateException;
use BapCat\Collection\NoSuchElementException;

abstract class HashMapHashIterator {
  /**
   * @var  HashMapNode|null
   */
  private $next = null;        // next entry to return

  /**
   * @var  HashMapNode|null
   */
  private $current = null;     // current entry

  /**
   * @var  int
   */
  private $index = 0;       // current slot

  public function __construct() {
    $t = table;

    if($t !== null && size > 0) { // advance to first entry
      do {} while($this->index < count($t) && ($this->next = $t[$this->index++]) === null);
    }
  }

  public function hasNext(): bool {
    return $this->next !== null;
  }

  protected function nextNode(): HashMapNode {
    $e = $this->next;

    if($e === null) {
      throw new NoSuchElementException();
    }

    if(($this->next = ($current = $e)->getNext()) === null && ($t = table) !== null) {
      do {} while($this->index < count($t) && ($this->next = $t[$this->index++]) === null);
    }

    return $e;
  }

  public function remove(): void {
    $p = $this->current;

    if($p === null) {
      throw new IllegalStateException();
    }

    $this->current = null;
    $key = $p->getKey();
    $this->removeNode($key, null, false, false);
  }
}
