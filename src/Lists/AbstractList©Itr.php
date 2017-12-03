<?php declare(strict_types=1); namespace BapCat\Collection\Lists;

use BapCat\Collection\IllegalStateException;
use BapCat\Collection\IndexOutOfBoundsException;
use BapCat\Collection\Iterator;
use BapCat\Collection\IteratorDefaultMethods;
use BapCat\Collection\NoSuchElementException;

/**
 * Default {@link Iterator} implementation for {@link AbstractList}
 */
class AbstractList©Itr implements Iterator {
  use IteratorDefaultMethods;

  /**
   * @var  AbstractList
   */
  private $thisList;

  /**
   * Index of element to be returned by subsequent call to next.
   *
   * @var  int
   */
  private $cursor = 0;

  /**
   * Index of element returned by most recent call to next or
   * previous.  Reset to -1 if this element is deleted by a call
   * to remove.
   *
   * @var  int
   */
  private $lastRet = -1;

  /**
   * @param  AbstractList  $list
   */
  public function __construct(AbstractList $list) {
    $this->thisList = $list;
  }

  /**
   * @return  bool
   */
  public function hasNext(): bool {
    return $this->cursor != $this->thisList->size();
  }

  /**
   * @return  mixed
   */
  public function next() {
    try {
      $i = $this->cursor;
      $next = $this->thisList->get($i);
      $this->lastRet = $i;
      $this->cursor = $i + 1;

      return $next;
    } catch(IndexOutOfBoundsException $e) {
      throw new NoSuchElementException();
    }
  }

  /**
   * @return  void
   */
  public function remove(): void {
    if($this->lastRet < 0) {
      throw new IllegalStateException();
    }

    try {
      $this->thisList->removeAt($this->lastRet);

      if($this->lastRet < $this->cursor) {
        $this->cursor--;
      }

      $this->lastRet = -1;
    } catch(IndexOutOfBoundsException $e) {
      throw new NoSuchElementException();
    }
  }

  /**
   * @return  bool
   */
  public function valid(): bool {
    try {
      $this->current();
      return true;
    } catch(IndexOutOfBoundsException $e) {
    }

    return false;
  }

  /**
   * @return  mixed
   */
  public function current() {
    return $this->thisList->get($this->cursor);
  }

  /**
   * @return  void
   */
  public function rewind(): void {
    $this->cursor = 0;
  }

  /**
   * @return  int
   */
  public function key(): int {
    return $this->cursor;
  }
}
