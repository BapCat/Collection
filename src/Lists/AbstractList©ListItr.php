<?php declare(strict_types=1); namespace BapCat\Collection\Lists;

use BapCat\Collection\IllegalStateException;
use BapCat\Collection\IndexOutOfBoundsException;
use BapCat\Collection\IteratorDefaultMethods;
use BapCat\Collection\NoSuchElementException;

/**
 * Default {@link ListIterator} implementation for {@link AbstractList}
 */
class AbstractList©ListItr extends AbstractList©Itr implements ListIterator {
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
   * @param  int           $index
   * @param  AbstractList  $list
   */
  public function __construct(int $index, AbstractList $list) {
    parent::__construct($list);

    $this->cursor = $index;
    $this->thisList = $list;
  }

  /**
   * @return  bool
   */
  public function hasPrevious() : bool {
    return $this->cursor != 0;
  }

  /**
   * @return  mixed
   */
  public function previous() {
    try {
      $i = $this->cursor - 1;
      $previous = $this->thisList->get($i);
      $this->lastRet = $this->cursor = $i;

      return $previous;
    } catch(IndexOutOfBoundsException $e) {
      throw new NoSuchElementException();
    }
  }

  /**
   * @return  int
   */
  public function nextIndex() : int {
    return $this->cursor;
  }

  /**
   * @return  int
   */
  public function previousIndex() : int {
    return $this->cursor - 1;
  }

  /**
   * @param  mixed  $value
   *
   * @return  void
   */
  public function set($value) : void {
    if($this->lastRet < 0) {
      throw new IllegalStateException();
    }

    try {
      $this->thisList->set($this->lastRet, $value);
    } catch(IndexOutOfBoundsException $ex) {
    }
  }

  /**
   * @param  mixed  $value
   *
   * @return  void
   */
  public function add($value) : void {
    try {
      $i = $this->cursor;
      $this->thisList->addAt($i, $value);
      $this->lastRet = -1;
      $this->cursor = $i + 1;
    } catch(IndexOutOfBoundsException $ex) {
    }
  }
}
