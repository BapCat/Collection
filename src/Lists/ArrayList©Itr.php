<?php declare(strict_types=1); namespace BapCat\Collection\Lists;

use BapCat\Collection\Functions\Consumer;
use BapCat\Collection\IllegalStateException;
use BapCat\Collection\Iterator;
use BapCat\Collection\NoSuchElementException;

/**
 * An optimized version of AbstractList.Itr
 */
class ArrayList©Itr implements Iterator {
  /**
   * Index of next element to return
   *
   * @var  int
   */
  protected $cursor;
  /**
   * Index of last element returned; -1 if no such
   *
   * @var  int
   */
  protected $lastRet = -1;

  /**
   * @var  int
   */
  protected $thisSize;

  /**
   * @var  array
   */
  protected $thisElementData;

  /**
   * @var  ArrayList
   */
  protected $thisThis;

  /**
   * @param  int        $size
   * @param  array      $elementData
   * @param  ArrayList  $thisThis
   */
  public function __construct(int &$size, array &$elementData, ArrayList &$thisThis) {
    $this->thisSize = &$size;
    $this->thisElementData = &$elementData;
    $this->thisThis = &$thisThis;

    $this->cursor = 0;
  }

  /**
   * @return  bool
   */
  public function hasNext(): bool {
    return $this->cursor != $this->thisSize;
  }

  /**
   * @return  mixed
   */
  public function next() {
    $i = $this->cursor;

    if($i >= $this->thisSize) {
      throw new NoSuchElementException();
    }

    $this->cursor = $i + 1;
    return $this->thisElementData[$this->lastRet = $i];
  }

  /**
   * @return  void
   */
  public function remove(): void {
    if($this->lastRet < 0) {
      throw new IllegalStateException();
    }

    $this->thisThis->removeAt($this->lastRet);
    $this->cursor = $this->lastRet;
    $this->lastRet = -1;
  }

  /**
   * @param  Consumer  $consumer
   *
   * @return  void
   */
  public function forEachRemaining(Consumer $consumer): void {
    for($i = $this->cursor; $i < $this->thisSize; $i++) {
      $consumer->accept($this->thisElementData[$i++]);
    }

    // update once at end of iteration to reduce heap write traffic
    $this->cursor = $i;
    $this->lastRet = $i - 1;
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
    return $this->thisElementData[$this->cursor];
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
