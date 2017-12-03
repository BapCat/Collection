<?php declare(strict_types=1); namespace BapCat\Collection\Lists;

use BapCat\Collection\Collection;
use BapCat\Collection\IllegalArgumentException;
use BapCat\Collection\IllegalStateException;
use BapCat\Collection\IndexOutOfBoundsException;
use BapCat\Collection\Iterator;
use BapCat\Collection\NoSuchElementException;
use BapCat\Collection\NullPointerException;

/**
 * Default {@link SubList} implementation for {@link AbstractList}
 */
class AbstractList©SubList extends AbstractList {
  /**
   * @var  AbstractList
   */
  private $l;

  /**
   * @var  int
   */
  private $offset;

  /**
   * @var  int
   */
  private $size;

  /**
   * @param  AbstractList  $lst
   * @param  int           $fromIndex
   * @param  int           $toIndex
   */
  public function __construct(AbstractList $lst, int $fromIndex, int $toIndex) {
    if($fromIndex < 0) {
      throw new IndexOutOfBoundsException("fromIndex = {$fromIndex}");
    }

    if($toIndex > $lst->size()) {
      throw new IndexOutOfBoundsException("toIndex = {$toIndex}");
    }

    if($fromIndex > $toIndex) {
      throw new IllegalArgumentException("fromIndex({$fromIndex}) > toIndex({$toIndex})");
    }

    $this->l = $lst;
    $this->offset = $fromIndex;
    $this->size = $toIndex - $fromIndex;
  }

  /**
   * @param  int    $index
   * @param  mixed  $element
   *
   * @return  mixed
   */
  public function setAt(int $index, $element) {
    $this->rangeCheck($index);
    return $this->l->set($index + $this->offset, $element);
  }

  /**
   * {@inheritdoc}
   *
   * @throws IndexOutOfBoundsException {@inheritdoc}
   */
  public function get(int $index) {
    $this->rangeCheck($index);
    return $this->l->get($index + $this->offset);
  }

  /**
   * {@inheritdoc}
   */
  public function size(): int {
    return $this->size;
  }

  /**
   * Inserts the specified element at the specified position in this list
   * (optional operation).  Shifts the element currently at that position
   * (if any) and any subsequent elements to the right (adds one to their
   * indices).
   *
   * @param  int    $index    The index at which the specified element is to be inserted
   * @param  mixed  $element  The element to be inserted
   *
   * @return  void
   *
   * @throws NullPointerException          {@inheritdoc}
   * @throws IllegalArgumentException      {@inheritdoc}
   * @throws IndexOutOfBoundsException     {@inheritdoc}
   */
  public function addAt(int $index, $element): void {
    $this->rangeCheckForAdd($index);
    $this->l->addAt($index + $this->offset, $element);
    $this->size++;
  }

  /**
   * Removes the element at the specified position in this list (optional
   * operation).  Shifts any subsequent elements to the left (subtracts one
   * from their indices).  Returns the element that was removed from the
   * list.
   *
   * @param  int  $index  The index of the element to be removed
   *
   * @return  mixed  The element previously at the specified position
   *
   * @throws IndexOutOfBoundsException     {@inheritdoc}
   */
  public function removeAt(int $index): bool {
    $this->rangeCheck($index);
    $result = $this->l->remove($index + $this->offset);
    $this->size--;
    return $result;
  }

  /**
   * @param  int  $fromIndex
   * @param  int  $toIndex
   *
   * @return  void
   */
  protected function removeRange(int $fromIndex, int $toIndex): void {
    $this->l->removeRange($fromIndex + $this->offset, $toIndex + $this->offset);
    $this->size -= ($toIndex - $fromIndex);
  }

  /**
   * <p>This implementation iterates over the specified collection, and adds
   * each object returned by the iterator to this collection, in turn.
   *
   * @param  Collection  $other  A collection containing elements to be added to this collection
   *
   * @return  bool  <tt>true</tt> if this collection changed as a result of the call
   *
   * @throws NullPointerException          {@inheritdoc}
   * @throws IllegalArgumentException      {@inheritdoc}
   * @throws IllegalStateException         {@inheritdoc}
   */
  public function addAll(Collection $other): bool {
    return $this->addAllAt($this->size, $other);
  }

  /**
   * This implementation gets an iterator over the specified collection
   * and iterates over it, inserting the elements obtained from the
   * iterator into this list at the appropriate position, one at a time,
   * using <tt>add(int, E)</tt>.
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
  public function addAllAt(int $index, Collection $other): bool {
    $this->rangeCheckForAdd($index);
    $cSize = $other->size();

    if($cSize === 0) {
      return false;
    }

    $this->l->addAllAt($this->offset + $index, $other);
    $this->size += $cSize;
    return true;
  }

  /**
   * Returns an iterator over the elements in this list in proper sequence.
   *
   * <p>This implementation returns a straightforward implementation of the
   * iterator interface, relying on the backing list's <tt>size()</tt>,
   * <tt>get(int)</tt>, and <tt>remove(int)</tt> methods.
   *
   * @return  Iterator  An iterator over the elements in this list in proper sequence
   */
  public function iterator(): Iterator {
    return $this->listIterator();
  }

  /**
   * <p>This implementation returns a straightforward implementation of the
   * <tt>ListIterator</tt> interface that extends the implementation of the
   * <tt>Iterator</tt> interface returned by the <tt>iterator()</tt> method.
   * The <tt>ListIterator</tt> implementation relies on the backing list's
   * <tt>get(int)</tt>, <tt>set(int, E)</tt>, <tt>add(int, E)</tt>
   * and <tt>remove(int)</tt> methods.
   *
   * @throws IndexOutOfBoundsException {@inheritdoc}
   */
  public function listIterator(int $index = 0): ListIterator {
    $this->rangeCheckForAdd($index);

    return new class($this, $this->offset, $this->size, $index) implements ListIterator {
      use ListIteratorDefaultMethods;

      /**
       * @var  ListIterator
       */
      private $i;

      /**
       * @var  AbstractList
       */
      private $thisL;

      /**
       * @var  int
       */
      private $thisOffset;

      /**
       * @var  int
       */
      private $thisSize;

      /**
       * @var  int
       */
      private $thisIndex;

      /**
       * @param  AbstractList  $l
       * @param  int           $offset
       * @param  int           $size
       * @param  int           $index
       */
      public function __construct(AbstractList $l, int &$offset, int &$size, int &$index) {
        $this->thisL = $l;
        $this->thisOffset = &$offset;
        $this->thisSize = &$size;
        $this->thisIndex = &$index;
        $this->i = $l->listIterator($index + $offset);
      }

      /**
       * @return  bool
       */
      public function hasNext(): bool {
        return $this->nextIndex() < $this->thisSize;
      }

      /**
       * @return  mixed
       */
      public function next() {
        if($this->hasNext()) {
          return $this->i->next();
        } else {
          throw new NoSuchElementException();
        }
      }

      /**
       * @return  bool
       */
      public function hasPrevious(): bool {
        return $this->previousIndex() >= 0;
      }

      /**
       * @return  mixed
       */
      public function previous() {
        if($this->hasPrevious()) {
          return $this->i->previous();
        } else {
          throw new NoSuchElementException();
        }
      }

      /**
       * @return  int
       */
      public function nextIndex(): int {
        return $this->i->nextIndex() - $this->thisOffset;
      }

      /**
       * @return  int
       */
      public function previousIndex(): int {
        return $this->i->previousIndex() - $this->thisOffset;
      }

      /**
       * @return  void
       */
      public function remove(): void {
        $this->i->remove();
        $this->thisSize--;
      }

      /**
       * @param  mixed  $value
       */
      public function set($value): void {
        $this->i->set($value);
      }

      /**
       * @param  mixed  $value
       */
      public function add($value): void {
        $this->i->add($value);
        $this->thisSize++;
      }

      /**
       * @return  bool
       */
      public function valid(): bool {
        return $this->thisIndex >= $this->thisOffset && $this->thisOffset + $this->thisIndex < $this->thisSize;
      }

      /**
       * @return  mixed
       */
      public function current() {
        return $this->thisL->get($this->thisIndex);
      }

      /**
       * @return  void
       */
      public function rewind(): void {
        $this->thisIndex = 0;
      }

      /**
       * @return  int
       */
      public function key(): int {
        return $this->thisIndex;
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
   * @throws  IndexOutOfBoundsException for an illegal endpoint index value
   *          (<tt>fromIndex < 0 || toIndex > size ||
   *          fromIndex > toIndex</tt>)
   */
  public function subList(int $fromIndex, int $toIndex): ListCollection {
    return new AbstractList©SubList($this, $fromIndex, $toIndex);
  }

  /**
   * @param  int  $index
   *
   * @return  void
   *
   * @throws IndexOutOfBoundsException if <tt>$index < 0 || $index > $this->size</tt>
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
   * @throws IndexOutOfBoundsException if <tt>$index < 0 || $index > $this->size</tt>
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
    return "Index: {$index}, Size: " . $this->size;
  }
}
