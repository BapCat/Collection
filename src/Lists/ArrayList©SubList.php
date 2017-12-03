<?php declare(strict_types=1); namespace BapCat\Collection\Lists;

use BapCat\Collection\Collection;
use BapCat\Collection\Functions\Consumer;
use BapCat\Collection\IllegalArgumentException;
use BapCat\Collection\IllegalStateException;
use BapCat\Collection\IndexOutOfBoundsException;
use BapCat\Collection\Iterator;
use BapCat\Collection\NoSuchElementException;
use BapCat\Collection\NullPointerException;

/**
 * ArrayList specialisation of SubList
 */
class ArrayList©SubList extends AbstractList {
  /**
   * @var  AbstractList  $parent
   */
  private $parent;

  /**
   * @var  ArrayList
   */
  private $arrayList;

  /**
   * @var  int
   */
  private $parentOffset;

  /**
   * @var  int
   */
  private $offset;

  /**
   * @var  int
   */
  private $size;

  /**
   * @var  array
   */
  private $elementData;

  /**
   * @param  AbstractList  $parent
   * @param  ArrayList     $arrayList
   * @param  int           $offset
   * @param  int           $fromIndex
   * @param  int           $toIndex
   * @param  array         $elementData
   */
  public function __construct(AbstractList $parent, ArrayList $arrayList, int $offset, int $fromIndex, int $toIndex, array &$elementData) {
    $this->parent = $parent;
    $this->arrayList = $arrayList;
    $this->parentOffset = $fromIndex;
    $this->offset = $offset + $fromIndex;
    $this->size = $toIndex - $fromIndex;

    $this->elementData = &$elementData;
  }

  /**
   * Replaces the element at the specified position in this list with the
   * specified element.
   *
   * @param  int    $index  The index of the element to replace
   * @param  mixed  $value  The element to be stored at the specified position
   *
   * @return  mixed  The element previously at the specified position
   *
   * @throws NullPointerException          {@inheritdoc}
   * @throws IllegalArgumentException      {@inheritdoc}
   * @throws IndexOutOfBoundsException     {@inheritdoc}
   */
  public function set(int $index, $e) {
    $this->rangeCheck($index);
    $oldValue = $this->elementData[$this->offset + $index];
    $this->elementData[$this->offset + $index] = $e;
    return $oldValue;
  }

  /**
   * Returns the element at the specified position in this list.
   *
   * @param  int  $index  The index of the element to return
   *
   * @return  mixed  The element at the specified position in this list
   *
   * @throws IndexOutOfBoundsException {@inheritdoc}
   */
  public function get(int $index) {
    $this->rangeCheck($index);
    return $this->elementData[$this->offset + $index];
  }

  /**
   * {@inheritdoc}
   *
   * @return  int
   */
  public function size(): int {
    return $this->size;
  }

  /**
   * Inserts the specified element at the specified position in this list.
   * Shifts the element currently at that position (if any) and any subsequent
   * elements to the right (adds one to their indices).
   *
   * @param  int    $index  The index at which the specified element is to be inserted
   * @param  mixed  $value  The element to be inserted
   *
   * @return  void
   *
   * @throws NullPointerException          {@inheritdoc}
   * @throws IllegalArgumentException      {@inheritdoc}
   * @throws IndexOutOfBoundsException     {@inheritdoc}
   */
  public function addAt(int $index, $e): void {
    $this->rangeCheckForAdd($index);
    $this->parent->addAt($this->parentOffset + $index, $e);
    $this->size++;
  }

  /**
   * Removes the element at the specified position in this list.  Shifts
   * any subsequent elements to the left (subtracts one from their indices).
   * Returns the element that was removed from the list.
   *
   * @param  int  $index  The index of the element to be removed
   *
   * @return  mixed  The element previously at the specified position
   *
   * @throws IndexOutOfBoundsException     {@inheritdoc}
   */
  public function removeAt(int $index) {
    $this->rangeCheck($index);
    $result = $this->parent->removeAt($this->parentOffset + $index);
    $this->size--;
    return $result;
  }

  /**
   * Removes from this list all of the elements whose index is between
   * <tt>fromIndex</tt>, inclusive, and <tt>toIndex</tt>, exclusive.
   * Shifts any succeeding elements to the left (reduces their index).
   * This call shortens the list by <tt>(toIndex - fromIndex)</tt> elements.
   * (If <tt>toIndex==fromIndex</tt>, this operation has no effect.)
   *
   * @param  int  $fromIndex  The index of first element to be removed
   * @param  int  $toIndex    The index after last element to be removed
   *
   * @throws  IndexOutOfBoundsException
   */
  protected function removeRange(int $fromIndex, int $toIndex): void {
    $this->parent->removeRange($this->parentOffset + $fromIndex, $this->parentOffset + $toIndex);
    $this->size -= $toIndex - $fromIndex;
  }

  /**
   * Adds all of the elements in the specified collection to this collection.  The behavior of
   * this operation is undefined if the specified collection is modified while the operation is
   * in progress. (This implies that the behavior of this call is undefined if the specified
   * collection is this collection, and this collection is nonempty.)
   *
   * @param  Collection  $other  A collection containing elements to be added to this collection
   *
   * @return  bool  <tt>true</tt> if this collection changed as a result of the call
   *
   * @throws NullPointerException          {@inheritdoc}
   * @throws IllegalArgumentException      {@inheritdoc}
   * @throws IllegalStateException         {@inheritdoc}
   */
  public function addAll(Collection $c): bool {
    return $this->addAllAt($this->size, $c);
  }

  /**
   * Inserts all of the elements in the specified collection into this list at the specified position.
   * Shifts the element currently at that position (if any) and any subsequent elements to the right (increases their
   * indices).  The new elements will appear in this list in the order that they are returned by the specified
   * collection's iterator.  The behavior of this operation is undefined if the specified collection is modified while
   * the operation is in progress.  (Note that this will occur if the specified collection is this list, and it's nonempty.)
   *
   * @param  int         $index  The index at which to insert the first element from the specified collection
   * @param  Collection  $other  The collection containing elements to be added to this list
   *
   * @return  bool  <tt>true</tt> if this list changed as a result of the call
   *
   * @throws NullPointerException          {@inheritdoc}
   * @throws IllegalArgumentException      {@inheritdoc}
   * @throws IndexOutOfBoundsException     {@inheritdoc}
   */
  public function addAllAt(int $index, Collection $c): bool {
    $this->rangeCheckForAdd($index);
    $cSize = $c->size();

    if($cSize === 0) {
      return false;
    }

    $this->parent->addAllAt($this->parentOffset + $index, $c);
    $this->size += $cSize;
    return true;
  }

  /**
   * Returns an iterator over the elements in this list in proper sequence.
   *
   * @return  Iterator  An iterator over the elements in this list in proper sequence
   */
  public function iterator(): Iterator {
    return $this->listIterator();
  }

  /**
   * Returns a list iterator over the elements in this list (in proper
   * sequence), optionally starting at the specified position in the list.
   * The specified index indicates the first element that would be
   * returned by an initial call to {@link ListIterator#next next}.
   * An initial call to {@link ListIterator#previous previous} would
   * return the element with the specified index minus one.
   *
   * @param  int  $index  The index of the first element to be returned from
   *         the list iterator (by a call to {@link ListIterator#next next})
   *
   * @return  ListIterator  A list iterator over the elements in this list (in
   *          proper sequence), starting at the specified position in the list
   *
   * @throws IndexOutOfBoundsException {@inheritdoc}
   */
  public function listIterator(int $index = 0): ListIterator {
    $this->rangeCheckForAdd($index);
    $offset = $this->offset;

    return new class($this, $this->elementData, $index, $offset) implements ListIterator {
      use ListIteratorDefaultMethods;

      /**
       * @var  ArrayList©SubList
       */
      private $subList;

      /**
       * @var  array
       */
      private $elementData;

      /**
       * @var  int
       */
      private $offset;

      /**
       * @var  int
       */
      private $cursor;

      /**
       * @var  int
       */
      private $lastRet = -1;

      /**
       * @param  ArrayList©SubList  $subList
       * @param  array              $elementData
       * @param  int                $index
       * @param  int                $offset
       */
      public function __construct(ArrayList©SubList $subList, array &$elementData, int $index, int $offset) {
        $this->subList = $subList;
        $this->elementData = &$elementData;
        $this->cursor = $index;
        $this->offset = $offset;
      }

      /**
       * @return  bool
       */
      public function hasNext(): bool {
        return $this->cursor != $this->subList->size();
      }

      /**
       * @return  mixed
       */
      public function next() {
        $i = $this->cursor;
        if($i >= $this->subList->size()) {
          throw new NoSuchElementException();
        }

        $this->cursor = $i + 1;
        return $this->elementData[$this->offset + ($this->lastRet = $i)];
      }

      /**
       * @return  bool
       */
      public function hasPrevious(): bool {
        return $this->cursor != 0;
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
        return $this->elementData[$this->offset + ($this->lastRet = $i)];
      }

      /**
       * @param  Consumer  $consumer
       *
       * @return  void
       */
      public function forEachRemaining(Consumer $consumer): void {
        $size = $this->subList->size();
        $i = $this->cursor;

        if($i >= $size) {
          return;
        }

        while($i != $size) {
          $consumer->accept($this->elementData[$this->offset + ($i++)]);
        }

        // update once at end of iteration to reduce heap write traffic
        $this->lastRet = $this->cursor = $i;
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
       * @return  void
       */
      public function remove(): void {
        if($this->lastRet < 0) {
          throw new IllegalStateException();
        }

        $this->subList->removeAt($this->lastRet);
        $this->cursor = $this->lastRet;
        $this->lastRet = -1;
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

        $this->subList->set($this->lastRet, $e);
      }

      /**
       * @param  mixed  $e
       *
       * @return  void
       */
      public function add($e): void {
        $this->subList->addAt($this->cursor, $e);
        $this->cursor++;
        $this->lastRet = -1;
      }

      /**
       * @return  bool
       */
      public function valid(): bool {
        return $this->cursor >= 0 && $this->cursor < $this->subList->size();
      }

      /**
       * @return  mixed
       */
      public function current() {
        return $this->elementData[$this->offset + $this->cursor];
      }

      /**
       * @return  void
       */
      public function rewind(): void {
        $this->cursor = 0;
        $this->lastRet = -1;
      }

      /**
       * @return  int
       */
      public function key(): int {
        return $this->cursor;
      }
    };
  }

  /**
   * Returns a view of the portion of this list between the specified
   * <tt>fromIndex</tt>, inclusive, and <tt>toIndex</tt>, exclusive.  (If
   * <tt>fromIndex</tt> and <tt>toIndex</tt> are equal, the returned list is
   * empty.)  The returned list is backed by this list, so non-structural
   * changes in the returned list are reflected in this list, and vice-versa.
   * The returned list supports all of the optional list operations supported
   * by this list.<p>
   *
   * This method eliminates the need for explicit range operations (of
   * the sort that commonly exist for arrays).  Any operation that expects
   * a list can be used as a range operation by passing a subList view
   * instead of a whole list.  For example, the following idiom
   * removes a range of elements from a list:
   *
   *      list.subList(from, to).clear();
   *
   * Similar idioms may be constructed for <tt>indexOf</tt> and
   * <tt>lastIndexOf</tt>, and all of the algorithms in the
   * <tt>Collections</tt> class can be applied to a subList.<p>
   *
   * The semantics of the list returned by this method become undefined if
   * the backing list (i.e., this list) is <i>structurally modified</i> in
   * any way other than via the returned list.  (Structural modifications are
   * those that change the size of this list, or otherwise perturb it in such
   * a fashion that iterations in progress may yield incorrect results.)
   *
   * @param  int  $fromIndex  The low endpoint (inclusive) of the subList
   * @param  int  $toIndex    The high endpoint (exclusive) of the subList
   *
   * @return  ListCollection  A view of the specified range within this list
   *
   * @throws IndexOutOfBoundsException if an endpoint index value is out of range
   *         <tt>(fromIndex < 0 || toIndex > size)</tt>
   * @throws IllegalArgumentException if the endpoint indices are out of order
   *         <tt>(fromIndex > toIndex)</tt>
   */
  public function subList(int $fromIndex, int $toIndex): ListCollection {
    $this->subListRangeCheck($fromIndex, $toIndex, $this->size);
    return new ArrayList©SubList($this, $this->arrayList, $this->offset, $fromIndex, $toIndex, $this->elementData);
  }

  /**
   * @param  int  $fromIndex
   * @param  int  $toIndex
   * @param  int  $size
   */
  private static function subListRangeCheck(int $fromIndex, int $toIndex, int $size) {
    if($fromIndex < 0) {
      throw new IndexOutOfBoundsException("fromIndex = {$fromIndex}");
    }

    if($toIndex > $size) {
      throw new IndexOutOfBoundsException("toIndex = {$toIndex}");
    }

    if($fromIndex > $toIndex) {
      throw new IllegalArgumentException("fromIndex({$fromIndex}) > toIndex({$toIndex})");
    }
  }

  /**
   * @param  int  $index
   *
   * @return  void
   */
  private function rangeCheck(int $index): void {
    if($index < 0 || $index >= $this->size) {
      throw new IndexOutOfBoundsException($this->outOfBoundsMsg($index));
    }
  }

  /**
   * @param  int  $index
   *
   * @return  void
   *
   * @throws IndexOutOfBoundsException if <tt>$index < 0 || $index > $this->size()</tt>
   */
  private function rangeCheckForAdd(int $index): void {
    if($index < 0 || $index > $this->size) {
      throw new IndexOutOfBoundsException($this->outOfBoundsMsg($index));
    }
  }

  /**
   * @param  int  $index
   *
   * @return  string
   */
  private function outOfBoundsMsg(int $index): string {
    return "Index: {$index}, Size: {$this->size}";
  }
}
