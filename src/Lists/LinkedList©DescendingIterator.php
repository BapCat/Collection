<?php declare(strict_types=1); namespace BapCat\Collection\Lists;
use BapCat\Collection\Iterator;
use BapCat\Collection\IteratorDefaultMethods;

/**
 * Adapter to provide descending iterators via ListItr.previous
 */
class LinkedList©DescendingIterator implements Iterator {
  use IteratorDefaultMethods;

  private $itr;

  public function __construct(LinkedList $list, callable $node, callable $linkBefore, callable $linkLast, callable $unlink, LinkedList©Node &$last, int $index) {
    $this->itr = new LinkedList©ListItr($list, $node, $linkBefore, $linkLast, $unlink, $last, $index);
  }

  public function hasNext(): bool {
    return $this->itr->hasPrevious();
  }

  public function next() {
    return $this->itr->previous();
  }

  public function remove(): void {
    $this->itr->remove();
  }

  /**
   * @return  bool
   */
  public function valid(): bool {
    return $this->itr->valid();
  }

  /**
   * @return  mixed
   */
  public function current() {
    return $this->itr->current();
  }

  /**
   * @return  void
   */
  public function rewind(): void {
    $this->itr->rewind();
  }

  /**
   * @return  int
   */
  public function key(): int {
    return $this->itr->key();
  }
}
