<?php declare(strict_types=1); namespace BapCat\Collection\Lists;

use BapCat\Collection\AbstractCollection;
use BapCat\Collection\Collection;
use BapCat\Collection\IllegalArgumentException;
use BapCat\Collection\IndexOutOfBoundsException;
use BapCat\Collection\Iterator;
use BapCat\Collection\NullPointerException;
use BapCat\Collection\UnsupportedOperationException;

/**
 * This class provides a skeletal implementation of the {@link List}
 * interface to minimize the effort required to implement this interface
 * backed by a "random access" data store (such as an array).  For sequential
 * access data (such as a linked list), {@link AbstractSequentialList} should
 * be used in preference to this class.
 *
 * <p>To implement an unmodifiable list, the programmer needs only to extend
 * this class and provide implementations for the {@link #get(int)} and
 * {@link List#size() size()} methods.
 *
 * <p>To implement a modifiable list, the programmer must additionally
 * override the {@link #set(int, Object) set(int, E)} method (which otherwise
 * throws an <tt>UnsupportedOperationException</tt>).  If the list is
 * variable-size the programmer must additionally override the
 * {@link #add(int, Object) add(int, E)} and {@link #remove(int)} methods.
 *
 * <p>The programmer should generally provide a void (no argument) and collection
 * constructor, as per the recommendation in the {@link Collection} interface
 * specification.
 *
 * <p>Unlike the other abstract collection implementations, the programmer does
 * <i>not</i> have to provide an iterator implementation; the iterator and
 * list iterator are implemented by this class, on top of the "random access"
 * methods:
 * {@link #get(int)},
 * {@link #set(int, Object) set(int, E)},
 * {@link #add(int, Object) add(int, E)} and
 * {@link #remove(int)}.
 *
 * <p>The documentation for each non-abstract method in this class describes its
 * implementation in detail.  Each of these methods may be overridden if the
 * collection being implemented admits a more efficient implementation.
 *
 * <p>This class is a member of the
 * <a href="{@docRoot}/../technotes/guides/collections/index.html">
 * Java Collections Framework</a>.
 */

abstract class AbstractList extends AbstractCollection implements ListCollection {
  use ListDefaultMethods;

  /**
   * Sole constructor.  (For invocation by subclass constructors, typically implicit.)
   */
  protected function __construct() {
  }

  /**
   * Appends the specified element to the end of this list (optional
   * operation).
   *
   * <p>Lists that support this operation may place limitations on what
   * elements may be added to this list.  In particular, some
   * lists will refuse to add null elements, and others will impose
   * restrictions on the type of elements that may be added.  List
   * classes should clearly specify in their documentation any restrictions
   * on what elements may be added.
   *
   * <p>This implementation calls <tt>add(size(), e)</tt>.
   *
   * <p>Note that this implementation throws an
   * <tt>UnsupportedOperationException</tt> unless
   * {@link #add(int, Object) add(int, E)} is overridden.
   *
   * @param  mixed  $value  The element to be appended to this list
   *
   * @return  bool  <tt>true</tt> (as specified by {@link Collection#add})
   *
   * @throws UnsupportedOperationException if the <tt>add</tt> operation
   *         is not supported by this list
   * @throws NullPointerException if the specified element is null and this
   *         list does not permit null elements
   * @throws IllegalArgumentException if some property of this element
   *         prevents it from being added to this list
   * @throws IndexOutOfBoundsException
   */
  public function add($value): bool {
    $this->addAt($this->size(), $value);
    return true;
  }

  /**
   * {@inheritdoc}
   *
   * @throws IndexOutOfBoundsException {@inheritdoc}
   */
  public abstract function get(int $index);

  /**
   * {@inheritdoc}
   *
   * <p>This implementation always throws an
   * <tt>UnsupportedOperationException</tt>.
   *
   * @throws UnsupportedOperationException {@inheritdoc}
   * @throws NullPointerException          {@inheritdoc}
   * @throws IllegalArgumentException      {@inheritdoc}
   * @throws IndexOutOfBoundsException     {@inheritdoc}
   */
  public function set(int $index, $element) {
    throw new UnsupportedOperationException();
  }

  /**
   * {@inheritdoc}
   *
   * <p>This implementation always throws an
   * <tt>UnsupportedOperationException</tt>.
   *
   * @throws UnsupportedOperationException {@inheritdoc}
   * @throws NullPointerException          {@inheritdoc}
   * @throws IllegalArgumentException      {@inheritdoc}
   * @throws IndexOutOfBoundsException     {@inheritdoc}
   */
  public function addAt(int $index, $element): void {
    throw new UnsupportedOperationException();
  }

  /**
   * {@inheritdoc}
   *
   * <p>This implementation always throws an
   * <tt>UnsupportedOperationException</tt>.
   *
   * @throws UnsupportedOperationException {@inheritdoc}
   * @throws IndexOutOfBoundsException     {@inheritdoc}
   */
  public function removeAt(int $index) {
    throw new UnsupportedOperationException();
  }

  // Search Operations

  /**
   * {@inheritdoc}
   *
   * <p>This implementation first gets a list iterator (with
   * <tt>listIterator()</tt>).  Then, it iterates over the list until the
   * specified element is found or the end of the list is reached.
   */
  public function indexOf($value): int {
    $it = $this->listIterator();

    if($value === null) {
      while($it->hasNext()) {
        if($it->next() === null) {
          return $it->previousIndex();
        }
      }
    } else {
      while($it->hasNext()) {
        if($value === $it->next()) {
          return $it->previousIndex();
        }
      }
    }

    return -1;
  }

  /**
   * {@inheritdoc}
   *
   * <p>This implementation first gets a list iterator that points to the end
   * of the list (with <tt>listIterator(size())</tt>).  Then, it iterates
   * backwards over the list until the specified element is found, or the
   * beginning of the list is reached.
   */
  public function lastIndexOf($value): int {
    $it = $this->listIterator($this->size());

    if($value === null) {
      while($it->hasPrevious()) {
        if($it->previous() === null) {
          return $it->nextIndex();
        }
      }
    } else {
      while($it->hasPrevious()) {
        if($value === $it->previous()) {
          return $it->nextIndex();
        }
      }
    }

    return -1;
  }

  // Bulk Operations

  /**
   * Removes all of the elements from this list (optional operation).
   * The list will be empty after this call returns.
   *
   * <p>This implementation calls <tt>removeRange(0, size())</tt>.
   *
   * <p>Note that this implementation throws an
   * <tt>UnsupportedOperationException</tt> unless <tt>remove(int
   * index)</tt> or <tt>removeRange(int fromIndex, int toIndex)</tt> is
   * overridden.
   *
   * @throws UnsupportedOperationException if the <tt>clear</tt> operation
   *         is not supported by this list
   */
  public function clear(): void {
    $this->removeRange(0, $this->size());
  }

  /**
   * {@inheritdoc}
   *
   * <p>This implementation gets an iterator over the specified collection
   * and iterates over it, inserting the elements obtained from the
   * iterator into this list at the appropriate position, one at a time,
   * using <tt>add(int, E)</tt>.
   * Many implementations will override this method for efficiency.
   *
   * <p>Note that this implementation throws an
   * <tt>UnsupportedOperationException</tt> unless
   * {@link #add(int, Object) add(int, E)} is overridden.
   *
   * @throws UnsupportedOperationException {@inheritdoc}
   * @throws NullPointerException          {@inheritdoc}
   * @throws IllegalArgumentException      {@inheritdoc}
   * @throws IndexOutOfBoundsException     {@inheritdoc}
   */
  public function addAllAt(int $index, Collection $other): bool {
    $this->rangeCheckForAdd($index);

    $modified = false;

    foreach($other as $e) {
      $this->addAt($index++, $e);
      $modified = true;
    }

    return $modified;
  }

  // Iterators

  /**
   * Returns an iterator over the elements in this list in proper sequence.
   *
   * <p>This implementation returns a straightforward implementation of the
   * iterator interface, relying on the backing list's <tt>size()</tt>,
   * <tt>get(int)</tt>, and <tt>remove(int)</tt> methods.
   *
   * <p>Note that the iterator returned by this method will throw an
   * {@link UnsupportedOperationException} in response to its
   * <tt>remove</tt> method unless the list's <tt>remove(int)</tt> method is
   * overridden.
   *
   * <p>This implementation can be made to throw runtime exceptions in the
   * face of concurrent modification, as described in the specification
   * for the (protected) {@link #modCount} field.
   *
   * @return  Iterator  An iterator over the elements in this list in proper sequence
   */
  public function iterator(): Iterator {
    return new AbstractList©Itr($this);
  }

  /**
   * {@inheritdoc}
   *
   * <p>This implementation returns a straightforward implementation of the
   * <tt>ListIterator</tt> interface that extends the implementation of the
   * <tt>Iterator</tt> interface returned by the <tt>iterator()</tt> method.
   * The <tt>ListIterator</tt> implementation relies on the backing list's
   * <tt>get(int)</tt>, <tt>set(int, E)</tt>, <tt>add(int, E)</tt>
   * and <tt>remove(int)</tt> methods.
   *
   * <p>Note that the list iterator returned by this implementation will
   * throw an {@link UnsupportedOperationException} in response to its
   * <tt>remove</tt>, <tt>set</tt> and <tt>add</tt> methods unless the
   * list's <tt>remove(int)</tt>, <tt>set(int, E)</tt>, and
   * <tt>add(int, E)</tt> methods are overridden.
   *
   * <p>This implementation can be made to throw runtime exceptions in the
   * face of concurrent modification, as described in the specification for
   * the (protected) {@link #modCount} field.
   *
   * @throws IndexOutOfBoundsException {@inheritdoc}
   */
  public function listIterator(int $index = 0): ListIterator {
    $this->rangeCheckForAdd($index);

    return new AbstractList©ListItr($index, $this);
  }

  /**
   * {@inheritdoc}
   *
   * <p>This implementation returns a list that subclasses
   * <tt>AbstractList</tt>.  The subclass stores, in private fields, the
   * offset of the subList within the backing list, the size of the subList
   * (which can change over its lifetime), and the expected
   * <tt>modCount</tt> value of the backing list.  There are two variants
   * of the subclass, one of which implements <tt>RandomAccess</tt>.
   * If this list implements <tt>RandomAccess</tt> the returned list will
   * be an instance of the subclass that implements <tt>RandomAccess</tt>.
   *
   * <p>The subclass's <tt>set(int, E)</tt>, <tt>get(int)</tt>,
   * <tt>add(int, E)</tt>, <tt>remove(int)</tt>, <tt>addAll(int,
   * Collection)</tt> and <tt>removeRange(int, int)</tt> methods all
   * delegate to the corresponding methods on the backing abstract list,
   * after bounds-checking the index and adjusting for the offset.  The
   * <tt>addAll(Collection c)</tt> method merely returns <tt>addAll(size,
   * c)</tt>.
   *
   * <p>The <tt>listIterator(int)</tt> method returns a "wrapper object"
   * over a list iterator on the backing list, which is created with the
   * corresponding method on the backing list.  The <tt>iterator</tt> method
   * merely returns <tt>listIterator()</tt>, and the <tt>size</tt> method
   * merely returns the subclass's <tt>size</tt> field.
   *
   * <p>All methods first check to see if the actual <tt>modCount</tt> of
   * the backing list is equal to its expected value, and throw a
   * <tt>ConcurrentModificationException</tt> if it is not.
   *
   * @throws IndexOutOfBoundsException if an endpoint index value is out of range
   *         <tt>(fromIndex < 0 || toIndex > size)</tt>
   * @throws IllegalArgumentException if the endpoint indices are out of order
   *         <tt>(fromIndex > toIndex)</tt>
   */
  public function subList(int $fromIndex, int $toIndex): ListCollection {
    return new AbstractList©SubList($this, $fromIndex, $toIndex);
  }

  // Comparison and hashing

  /**
   * Compares the specified object with this list for equality.  Returns
   * <tt>true</tt> if and only if the specified object is also a list, both
   * lists have the same size, and all corresponding pairs of elements in
   * the two lists are <i>equal</i>.  (Two elements <tt>e1</tt> and
   * <tt>e2</tt> are <i>equal</i> if <tt>(e1==null ? e2==null :
   * e1.equals(e2))</tt>.)  In other words, two lists are defined to be
   * equal if they contain the same elements in the same order.<p>
   *
   * This implementation first checks if the specified object is this
   * list. If so, it returns <tt>true</tt>; if not, it checks if the
   * specified object is a list. If not, it returns <tt>false</tt>; if so,
   * it iterates over both lists, comparing corresponding pairs of elements.
   * If any comparison returns <tt>false</tt>, this method returns
   * <tt>false</tt>.  If either iterator runs out of elements before the
   * other it returns <tt>false</tt> (as the lists are of unequal length);
   * otherwise it returns <tt>true</tt> when the iterations complete.
   *
   * @param  mixed  $other  The object to be compared for equality with this list
   *
   * @return  bool  If the specified object is equal to this list
   *
   * @throws  IndexOutOfBoundsException
   */
  public function equals($other): bool {
    if($other === $this) {
      return true;
    }

    if(!($other instanceof ListCollection)) {
      return false;
    }

    $e1 = $this->listIterator();
    $e2 = $other->listIterator();

    while($e1->hasNext() && $e2->hasNext()) {
      $o1 = $e1->next();
      $o2 = $e2->next();

      if(!($o1 === null ? $o2 === null : $o1 === $o2)) {
        return false;
      }
    }

    return !($e1->hasNext() || $e2->hasNext());
  }

  /**
   * Removes from this list all of the elements whose index is between
   * <tt>fromIndex</tt>, inclusive, and <tt>toIndex</tt>, exclusive.
   * Shifts any succeeding elements to the left (reduces their index).
   * This call shortens the list by <tt>(toIndex - fromIndex)</tt> elements.
   * (If <tt>toIndex==fromIndex</tt>, this operation has no effect.)
   *
   * <p>This method is called by the <tt>clear</tt> operation on this list
   * and its subLists.  Overriding this method to take advantage of
   * the internals of the list implementation can <i>substantially</i>
   * improve the performance of the <tt>clear</tt> operation on this list
   * and its subLists.
   *
   * <p>This implementation gets a list iterator positioned before
   * <tt>fromIndex</tt>, and repeatedly calls <tt>ListIterator.next</tt>
   * followed by <tt>ListIterator.remove</tt> until the entire range has
   * been removed.  <b>Note: if <tt>ListIterator.remove</tt> requires linear
   * time, this implementation requires quadratic time.</b>
   *
   * @param  int  $fromIndex  The index of first element to be removed
   * @param  int  $toIndex    The index after last element to be removed
   *
   * @throws  IndexOutOfBoundsException
   */
  protected function removeRange(int $fromIndex, int $toIndex): void {
    $it = $this->listIterator($fromIndex);

    for($i = 0, $n = $toIndex - $fromIndex; $i < $n; $i++) {
      $it->next();
      $it->remove();
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
    if($index < 0 || $index > $this->size()) {
      throw new IndexOutOfBoundsException($this->outOfBoundsMsg($index));
    }
  }

  /**
   * @param  int  $index
   *
   * @return  string
   */
  private function outOfBoundsMsg(int $index): string {
    return "Index: {$index}, Size: {$this->size()}";
  }
}
