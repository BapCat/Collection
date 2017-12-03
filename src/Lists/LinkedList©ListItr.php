<?php declare(strict_types=1); namespace BapCat\Collection\Lists;

use BapCat\Collection\Functions\Consumer;
use BapCat\Collection\IllegalStateException;
use BapCat\Collection\NoSuchElementException;

class LinkedList©ListItr implements ListIterator {
  use ListIteratorDefaultMethods;

  /**
   * @var  LinkedList
   */
  private $list;

  /**
   * @var  callable
   */
  private $node;

  /**
   * @var  callable
   */
  private $linkBefore;

  /**
   * @var  callable
   */
  private $linkLast;

  /**
   * @var  callable
   */
  private $unlink;

  /**
   * @var  LinkedList©Node
   */
  private $last;

  /**
   * @var  LinkedList©Node
   */
  private $lastReturned;

  /**
   * @var  int
   */
  private $startingIndex;

  /**
   * @var  LinkedList©Node
   */
  private $next;

  /**
   * @var  int
   */
  private $nextIndex;

  public function __construct(LinkedList $list, callable $node, callable $linkBefore, callable $linkLast, callable $unlink, LinkedList©Node &$last, int $index) {
    $this->list = $list;
    $this->node = $node;
    $this->linkBefore = $linkBefore;
    $this->linkLast = $linkLast;
    $this->unlink = $unlink;
    $this->last = &$last;
    $this->startingIndex = $index;
    $this->next = ($index === $list->size()) ? null : $node($index);
    $this->nextIndex = $index;
  }

  public function hasNext(): bool {
    return $this->nextIndex < $this->list->size();
  }

  public function next() {
    if(!$this->hasNext()) {
      throw new NoSuchElementException();
    }

    $this->lastReturned = $this->next;
    $this->next = $this->next->next;
    $this->nextIndex++;
    return $this->lastReturned->item;
  }

  public function hasPrevious(): bool {
    return $this->nextIndex > 0;
  }

  public function previous() {
    if(!$this->hasPrevious()) {
      throw new NoSuchElementException();
    }

    $this->lastReturned = $this->next = ($this->next == null) ? $this->last : $this->next->prev;
    $this->nextIndex--;
    return $this->lastReturned->item;
  }

  public function nextIndex(): int {
    return $this->nextIndex;
  }

  public function previousIndex(): int {
    return $this->nextIndex - 1;
  }

  public function remove(): void {
    if($this->lastReturned === null) {
      throw new IllegalStateException();
    }

    $lastNext = $this->lastReturned->next;
    ($this->unlink)($this->lastReturned);

    if($this->next === $this->lastReturned) {
      $this->next = $lastNext;
    } else {
      $this->nextIndex--;
    }

    $this->lastReturned = null;
  }

  public function set($e): void {
    if($this->lastReturned === null) {
      throw new IllegalStateException();
    }

    $this->lastReturned->item = $e;
  }

  public function add($e): void {
    $this->lastReturned = null;

    if($this->next === null) {
      ($this->linkLast)($e);
    } else {
      ($this->linkBefore)($e, $this->next);
    }

    $this->nextIndex++;
  }

  public function forEachRemaining(Consumer $action): void {
    while($this->nextIndex < $this->list->size()) {
      $action->accept($this->next->item);
      $this->lastReturned = $this->next;
      $this->next = $this->next->next;
      $this->nextIndex++;
    }
  }

  /**
   * @return  bool
   */
  public function valid(): bool {
    return $this->hasNext();
  }

  /**
   * @return  mixed
   */
  public function current() {
    return $this->next->item;
  }

  /**
   * @return  void
   */
  public function rewind(): void {
    $this->next = ($this->startingIndex === $this->list->size()) ? null : ($this->node)($this->startingIndex);
    $this->nextIndex = $this->startingIndex;
    $this->lastReturned = null;
  }

  /**
   * @return  int
   */
  public function key(): int {
    return $this->nextIndex;
  }
}
