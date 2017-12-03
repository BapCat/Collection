<?php declare(strict_types=1); namespace BapCat\Collection\Lists;

use BapCat\Collection\IllegalStateException;
use BapCat\Collection\NoSuchElementException;

/**
 * An optimized version of AbstractList.ListItr
 */
class ArrayList©ListItr extends ArrayList©Itr implements ListIterator {
  /**
   * @param  int        $index
   * @param  int        $size
   * @param  array      $elementData
   * @param  ArrayList  $thisThis
   */
  public function __construct(int $index, int &$size, array &$elementData, ArrayList &$thisThis) {
    parent::__construct($size, $elementData, $thisThis);
    $this->cursor = $index;
  }

  /**
   * @return  bool
   */
  public function hasPrevious(): bool {
    return $this->cursor != 0;
  }

  /**
   * @return  int
   */
  public function nextIndex(): int {
    return $this->cursor;
  }

  /**
   * @return  int
   */
  public function previousIndex(): int {
    return $this->cursor - 1;
  }

  /**
   * @return  mixed
   */
  public function previous() {
    $i = $this->cursor - 1;

    if($i < 0) {
      throw new NoSuchElementException();
    }

    $this->cursor = $i;
    return $this->thisElementData[$this->lastRet = $i];
  }

  /**
   * @param  mixed  $e
   *
   * @return  void
   */
  public function set($e): void {
    if($this->lastRet < 0) {
      throw new IllegalStateException();
    }

    $this->thisThis->set($this->lastRet, $e);
  }

  /**
   * @param  mixed  $e
   *
   * @return  void
   */
  public function add($e): void {
    $i = $this->cursor;
    $this->thisThis->addAt($i, $e);
    $this->cursor = $i + 1;
    $this->lastRet = -1;
  }
}
