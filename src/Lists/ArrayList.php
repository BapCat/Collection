<?php declare(strict_types=1); namespace BapCat\Collection\Lists;

use BapCat\Collection\Collection;
use BapCat\Collection\Comparator;
use BapCat\Collection\Functions\Consumer;
use BapCat\Collection\Functions\Predicate;
use BapCat\Collection\Functions\UnaryOperator;
use BapCat\Collection\IllegalArgumentException;
use BapCat\Collection\IllegalStateException;
use BapCat\Collection\IndexOutOfBoundsException;
use BapCat\Collection\Iterator;
use BapCat\Collection\NoSuchElementException;
use BapCat\Collection\NullPointerException;

/**
 * Resizable-array implementation of the <tt>List</tt> interface.  Implements
 * all optional list operations, and permits all elements, including
 * <tt>null</tt>.  In addition to implementing the <tt>List</tt> interface,
 * this class provides methods to manipulate the size of the array that is
 * used internally to store the list.
 *
 * <p>The <tt>size</tt>, <tt>isEmpty</tt>, <tt>get</tt>, <tt>set</tt>,
 * <tt>iterator</tt>, and <tt>listIterator</tt> operations run in constant
 * time.  The <tt>add</tt> operation runs in <i>amortized constant time</i>,
 * that is, adding n elements requires O(n) time.  All of the other operations
 * run in linear time (roughly speaking).  The constant factor is low compared
 * to that for the <tt>LinkedList</tt> implementation.
 *
 * <p>Each <tt>ArrayList</tt> instance has a <i>capacity</i>.  The capacity is
 * the size of the array used to store the elements in the list.  It is always
 * at least as large as the list size.  As elements are added to an ArrayList,
 * its capacity grows automatically.  The details of the growth policy are not
 * specified beyond the fact that adding an element has constant amortized
 * time cost.
 *
 * <p>An application can increase the capacity of an <tt>ArrayList</tt> instance
 * before adding a large number of elements using the <tt>ensureCapacity</tt>
 * operation.  This may reduce the amount of incremental reallocation.
 *
 * <p><a name="fail-fast">
 * The iterators returned by this class's {@link #iterator() iterator} and
 * {@link #listIterator(int) listIterator} methods are <em>fail-fast</em>:</a>
 * if the list is structurally modified at any time after the iterator is
 * created, in any way except through the iterator's own
 * {@link ListIterator#remove() remove} or
 * {@link ListIterator#add(Object) add} methods, the iterator will throw a
 * {@link ConcurrentModificationException}.  Thus, in the face of
 * concurrent modification, the iterator fails quickly and cleanly, rather
 * than risking arbitrary, non-deterministic behavior at an undetermined
 * time in the future.
 *
 * <p>This class is a member of the
 * <a href="{@docRoot}/../technotes/guides/collections/index.html">
 * Java Collections Framework</a>.
 */

class ArrayList extends AbstractList implements ListCollection {
  /**
   * The array buffer into which the elements of the ArrayList are stored.
   * The capacity of the ArrayList is the length of this array buffer. Any
   * empty ArrayList with elementData == DEFAULTCAPACITY_EMPTY_ELEMENTDATA
   * will be expanded to DEFAULT_CAPACITY when the first element is added.
   *
   * @var  mixed[]
   */
  private $elementData;

  /**
   * @var  int
   */
  private $size;

  /**
   * Constructs a list containing the elements of the specified
   * collection, in the order they are returned by the collection's
   * iterator.
   *
   * @param  Collection|null  $other  The collection whose elements are to be placed into this list
   */
  public function __construct(?Collection $other = null) {
    $this->elementData = $other !== null ? $other->toArray() : [];
    $this->size = count($this->elementData);
  }

  /**
   * Returns the number of elements in this list.
   *
   * @return  int  The number of elements in this list
   */
  public function size(): int {
    return $this->size;
  }

  /**
   * Returns <tt>true</tt> if this list contains no elements.
   *
   * @return  bool  <tt>true</tt> if this list contains no elements
   */
  public function isEmpty(): bool {
      return $this->size === 0;
  }

  /**
   * Returns <tt>true</tt> if this list contains the specified element.
   * More formally, returns <tt>true</tt> if and only if this list contains
   * at least one element <tt>e</tt> such that
   * <tt>(o==null&nbsp;?&nbsp;e==null&nbsp;:&nbsp;o.equals(e))</tt>.
   *
   * @param  mixed  $value  The element whose presence in this list is to be tested
   *
   * @return  bool  <tt>true</tt> if this list contains the specified element
   */
  public function contains($value): bool {
    return $this->indexOf($value) >= 0;
  }

  /**
   * Returns the index of the first occurrence of the specified element
   * in this list, or -1 if this list does not contain the element.
   * More formally, returns the lowest index <tt>i</tt> such that
   * <tt>(o==null&nbsp;?&nbsp;get(i)==null&nbsp;:&nbsp;o.equals(get(i)))</tt>,
   * or -1 if there is no such index.
   *
   * @param  int  $value
   *
   * @return  int
   */
  public function indexOf($value): int {
    for($i = 0; $i < $this->size; $i++) {
      if($value === $this->elementData[$i]) {
        return $i;
      }
    }

    return -1;
  }

  /**
   * Returns the index of the last occurrence of the specified element
   * in this list, or -1 if this list does not contain the element.
   * More formally, returns the highest index <tt>i</tt> such that
   * <tt>(o==null&nbsp;?&nbsp;get(i)==null&nbsp;:&nbsp;o.equals(get(i)))</tt>,
   * or -1 if there is no such index.
   *
   * @param  int  $value
   *
   * @return  int
   */
  public function lastIndexOf($value) : int {
    for($i = $this->size - 1; $i >= 0; $i--) {
      if($value === $this->elementData[$i]) {
        return $i;
      }
    }

    return -1;
  }

  /**
   * Returns an array containing all of the elements in this list
   * in proper sequence (from first to last element).
   *
   * <p>The returned array will be "safe" in that no references to it are
   * maintained by this list.  (In other words, this method must allocate
   * a new array).  The caller is thus free to modify the returned array.
   *
   * <p>This method acts as bridge between array-based and collection-based
   * APIs.
   *
   * @return  array  An array containing all of the elements in this list in
   *                 proper sequence
   */
  public function toArray(): array {
    return $this->elementData;
  }

  /**
   * Returns the element at the specified position in this list.
   *
   * @param  int  $index  The index of the element to return
   *
   * @return  mixed The element at the specified position in this list
   *
   * @throws IndexOutOfBoundsException {@inheritdoc}
   */
  public function get(int $index) {
    $this->rangeCheck($index);

    return $this->elementData[$index];
  }

  /**
   * Replaces the element at the specified position in this list with
   * the specified element.
   *
   * @param  int    $index    The index of the element to replace
   * @param  mixed  $element  The element to be stored at the specified position
   *
   * @return  mixed  The element previously at the specified position
   *
   * @throws IndexOutOfBoundsException {@inheritdoc}
   */
  public function set(int $index, $element) {
    $this->rangeCheck($index);

    $oldValue = $this->elementData[$index];
    $this->elementData[$index] = $element;
    return $oldValue;
  }

  /**
   * Appends the specified element to the end of this list.
   *
   * @param  mixed  $e  The element to be appended to this list
   *
   * @return  bool  <tt>true</tt> (as specified by {@link Collection#add})
   */
  public function add($e): bool {
    $this->elementData[$this->size++] = $e;
    return true;
  }

  /**
   * Inserts the specified element at the specified position in this
   * list. Shifts the element currently at that position (if any) and
   * any subsequent elements to the right (adds one to their indices).
   *
   * @param  int    $index    The index at which the specified element is to be inserted
   * @param  mixed  $element  The element to be inserted
   *
   * @throws IndexOutOfBoundsException {@inheritdoc}
   */
  public function addAt(int $index, $element): void {
    $this->rangeCheckForAdd($index);

    array_splice($this->elementData, $index, 0, [$element]);
    $this->size++;
  }

  /**
   * Removes the element at the specified position in this list.
   * Shifts any subsequent elements to the left (subtracts one from their
   * indices).
   *
   * @param  int  $index  The index of the element to be removed
   *
   * @return  mixed  The element that was removed from the list
   *
   * @throws IndexOutOfBoundsException {@inheritdoc}
   */
  public function removeAt(int $index) {
    $this->rangeCheck($index);

    $oldValue = $this->elementData[$index];

    array_splice($this->elementData, $index, 1);
    $this->size--;

    return $oldValue;
  }

  /**
   * Removes the first occurrence of the specified element from this list,
   * if it is present.  If the list does not contain the element, it is
   * unchanged.  More formally, removes the element with the lowest index
   * <tt>i</tt> such that
   * <tt>(o==null&nbsp;?&nbsp;get(i)==null&nbsp;:&nbsp;o.equals(get(i)))</tt>
   * (if such an element exists).  Returns <tt>true</tt> if this list
   * contained the specified element (or equivalently, if this list
   * changed as a result of the call).
   *
   * @param  mixed  $o  The element to be removed from this list, if present
   *
   * @return  bool  <tt>true</tt> if this list contained the specified element
   */
  public function remove($o): bool {
    for($index = 0; $index < $this->size; $index++) {
      if($o === $this->elementData[$index]) {
        $this->fastRemove($index);

        return true;
      }
    }

    return false;
  }

  /**
   * Private remove method that skips bounds checking and does not
   * return the value removed.
   *
   * @param  int
   *
   * @return  void
   */
  private function fastRemove(int $index): void {
    array_splice($this->elementData, $index, 1);
    $this->size--;
  }

  /**
   * Removes all of the elements from this list.  The list will
   * be empty after this call returns.
   *
   * @return  void
   */
  public function clear(): void {
    $this->elementData = [];
    $this->size = 0;
  }

  /**
   * Appends all of the elements in the specified collection to the end of
   * this list, in the order that they are returned by the
   * specified collection's Iterator.  The behavior of this operation is
   * undefined if the specified collection is modified while the operation
   * is in progress.  (This implies that the behavior of this call is
   * undefined if the specified collection is this list, and this
   * list is nonempty.)
   *
   * @param  Collection  $c  A collection containing elements to be added to this list
   *
   * @return  bool  <tt>true</tt> if this list changed as a result of the call
   *
   * @throws NullPointerException if the specified collection is null
   */
  public function addAll(Collection $c): bool {
    $a = $c->toArray();
    $numNew = $c->size();
    $this->elementData = array_merge($this->elementData, $a);
    $this->size += $numNew;
    return $numNew != 0;
  }

  /**
   * Inserts all of the elements in the specified collection into this
   * list, starting at the specified position.  Shifts the element
   * currently at that position (if any) and any subsequent elements to
   * the right (increases their indices).  The new elements will appear
   * in the list in the order that they are returned by the
   * specified collection's iterator.
   *
   * @param  int         $index  The index at which to insert the first element from the
   *                             specified collection
   * @param  Collection  $c      collection containing elements to be added to this list
   *
   * @return  bool  <tt>true</tt> if this list changed as a result of the call
   *
   * @throws IndexOutOfBoundsException {@inheritDoc}
   * @throws NullPointerException if the specified collection is null
   */
  public function addAllAt(int $index, Collection $c): bool {
    $this->rangeCheckForAdd($index);

    $a = $c->toArray();
    $numNew = $c->size();

    array_splice($this->elementData, $index, 0, $a);
    $this->size += $numNew;
    return $numNew != 0;
  }

  /**
   * Removes from this list all of the elements whose index is between
   * <tt>fromIndex</tt>, inclusive, and <tt>toIndex</tt>, exclusive.
   * Shifts any succeeding elements to the left (reduces their index).
   * This call shortens the list by {@code (toIndex - fromIndex)} elements.
   * (If <tt>toIndex==fromIndex</tt>, this operation has no effect.)
   *
   * @param  int  $fromIndex
   * @param  int  $toIndex
   *
   * @throws IndexOutOfBoundsException if <tt>fromIndex</tt> or
   *         <tt>toIndex</tt> is out of range
   *         (<tt>fromIndex < 0 ||
   *          fromIndex >= size() ||
   *          toIndex > size() ||
   *          toIndex < fromIndex</tt>)
   */
  protected function removeRange(int $fromIndex, int $toIndex): void {
    $numRemoved = $toIndex - $fromIndex;

    array_splice($this->elementData, $fromIndex, $numRemoved);
    $this->size -= $numRemoved;
  }

  /**
   * Checks if the given index is in range.  If not, throws an appropriate
   * runtime exception.  This method does *not* check if the index is
   * negative: It is always used immediately prior to an array access,
   * which throws an ArrayIndexOutOfBoundsException if index is negative.
   *
   * @param  int  $index
   *
   * @return  void
   */
  private function rangeCheck(int $index): void {
    if($index >= $this->size) {
      throw new IndexOutOfBoundsException($this->outOfBoundsMsg($index));
    }
  }

  /**
   * A version of rangeCheck used by add and addAll.
   *
   * @param  int  $index
   *
   * @return  void
   */
  private function rangeCheckForAdd(int $index): void {
    if($index > $this->size || $index < 0) {
      throw new IndexOutOfBoundsException($this->outOfBoundsMsg($index));
    }
  }

  /**
   * Constructs an IndexOutOfBoundsException detail message.
   * Of the many possible refactorings of the error handling code,
   * this "outlining" performs best with both server and client VMs.
   *
   * @param  int  $index
   *
   * @return  string
   */
  private function outOfBoundsMsg(int $index): string {
    return "Index: {$index}, Size: {$this->size}";
  }

  /**
   * Removes from this list all of its elements that are contained in the
   * specified collection.
   *
   * @param  Collection  $c  A collection containing elements to be removed from this list
   *
   * @return  bool <tt>true</tt> if this list changed as a result of the call
   *
   * @throws NullPointerException if this list contains a null element and the
   *         specified collection does not permit null elements
   */
  public function removeAll(Collection $c): bool {
    return $this->batchRemove($c, false);
  }

  /**
   * Retains only the elements in this list that are contained in the
   * specified collection.  In other words, removes from this list all
   * of its elements that are not contained in the specified collection.
   *
   * @param  Collection  $c  A collection containing elements to be retained in this list
   *
   * @return  bool <tt>true</tt> if this list changed as a result of the call
   *
   * @throws NullPointerException if this list contains a null element and the
   *         specified collection does not permit null elements
   */
  public function retainAll(Collection $c): bool {
    return $this->batchRemove($c, true);
  }

  /**
   * @param  Collection  $c
   * @param  bool        $complement
   *
   * @return  bool
   */
  private function batchRemove(Collection $c, bool $complement): bool {
    $r = 0;
    $w = 0;
    $modified = false;

    try {
      for(; $r < $this->size; $r++) {
        if($c->contains($this->elementData[$r]) == $complement) {
          $this->elementData[$w++] = $this->elementData[$r];
        }
      }
    } finally {
      // Preserve behavioral compatibility with AbstractCollection,
      // even if c.contains() throws.
      if($r != $this->size) {
        array_splice($this->elementData, $r, $r - $w);
        $w += $this->size - $r;
      }

      if($w != $this->size) {
        // clear to let GC do its work
        for($i = $w; $i < $this->size; $i++) {
          $this->elementData[$i] = null;
        }

        $this->size = $w;
        $modified = true;
      }
    }

    return $modified;
  }

  /**
   * Returns a list iterator over the elements in this list (in proper
   * sequence), starting at the specified position in the list.
   * The specified index indicates the first element that would be
   * returned by an initial call to {@link ListIterator#next next}.
   * An initial call to {@link ListIterator#previous previous} would
   * return the element with the specified index minus one.
   *
   * <p>The returned list iterator is <a href="#fail-fast"><i>fail-fast</i></a>.
   *
   * @throws IndexOutOfBoundsException {@inheritdoc}
   */
  public function listIterator(int $index = 0): ListIterator {
    if($index < 0 || $index > $this->size) {
      throw new IndexOutOfBoundsException("Index: {$index}");
    }

    return new ArrayList©ListItr($index, $this->size, $this->elementData, $this);
  }

  /**
   * Returns an iterator over the elements in this list in proper sequence.
   *
   * <p>The returned iterator is <a href="#fail-fast"><i>fail-fast</i></a>.
   *
   * @return  Iterator  An iterator over the elements in this list in proper sequence
   */
  public function iterator(): Iterator {
    return new ArrayList©Itr($this->size, $this->elementData, $this);
  }

  /**
   * Returns a view of the portion of this list between the specified
   * <tt>fromIndex</tt>, inclusive, and <tt>toIndex<</tt>, exclusive.  (If
   * <tt>fromIndex</tt> and <tt>toIndex</tt> are equal, the returned list is
   * empty.)  The returned list is backed by this list, so non-structural
   * changes in the returned list are reflected in this list, and vice-versa.
   * The returned list supports all of the optional list operations.
   *
   * <p>This method eliminates the need for explicit range operations (of
   * the sort that commonly exist for arrays).  Any operation that expects
   * a list can be used as a range operation by passing a subList view
   * instead of a whole list.  For example, the following idiom
   * removes a range of elements from a list:
   *
   *      list.subList(from, to).clear();
   *
   * Similar idioms may be constructed for {@link #indexOf(Object)} and
   * {@link #lastIndexOf(Object)}, and all of the algorithms in the
   * {@link Collections} class can be applied to a subList.
   *
   * <p>The semantics of the list returned by this method become undefined if
   * the backing list (i.e., this list) is <i>structurally modified</i> in
   * any way other than via the returned list.  (Structural modifications are
   * those that change the size of this list, or otherwise perturb it in such
   * a fashion that iterations in progress may yield incorrect results.)
   *
   * @throws IndexOutOfBoundsException {@inheritdoc}
   * @throws IllegalArgumentException {@inheritdoc}
   */
  public function subList(int $fromIndex, int $toIndex): ListCollection {
    $this->subListRangeCheck($fromIndex, $toIndex, $this->size);
    return new ArrayList©SubList($this, $this, 0, $fromIndex, $toIndex, $this->elementData);
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
   * @param  Consumer  $action
   */
  public function each(Consumer $action): void {
    $size = $this->size();

    for($i = 0; $i < $size; $i++) {
      $action->accept($this->elementData[$i]);
    }
  }

  /**
   * {@inheritdoc}
   *
   * @param  Predicate  $filter
   *
   * @return  bool
   *
   * @throws  IllegalStateException
   * @throws  NoSuchElementException
   */
  public function removeIf(Predicate $filter): bool {
    // figure out which elements are to be removed
    // any exception thrown from the filter predicate at this stage
    // will leave the collection unmodified
    $removeCount = 0;
    $removeSet = new BitSet($this->size);
    $size = $this->size();

    for($i = 0; $i < $size; $i++) {
      $element = $this->elementData[$i];

      if($filter->test($element)) {
        $removeSet->set($i);
        $removeCount++;
      }
    }

    // shift surviving elements left over the spaces left by removed elements
    $anyToRemove = $removeCount > 0;
    if($anyToRemove) {
      $newSize = $size - $removeCount;
      for($i = 0, $j = 0; ($i < $size) && ($j < $newSize); $i++, $j++) {
        $i = $removeSet->nextClearBit($i);
        $this->elementData[$j] = $this->elementData[$i];
      }

      $this->elementData = array_slice($this->elementData, 0, $newSize);
      $this->size = $newSize;
    }

    return $anyToRemove;
  }

  /**
   * {@inheritdoc}
   *
   * @param  UnaryOperator  $operator
   *
   * @return  void
   */
  public function replaceAll(UnaryOperator $operator): void {
    $size = $this->size;

    for($i = 0; $i < $size; $i++) {
      $this->elementData[$i] = $operator->apply($this->elementData[$i]);
    }
  }

  /**
   * {@inheritdoc}
   *
   * @param  Comparator|null  $c
   *
   * @return  void
   */
  public function sort(?Comparator $c): void {
    if($c === null) {
      sort($this->elementData);
    } else {
      usort($this->elementData, [$c, 'compare']);
    }
  }
}
