<?php declare(strict_types=1); namespace BapCat\Collection;

/**
 * This class provides a skeletal implementation of the <tt>Collection</tt>
 * interface, to minimize the effort required to implement this interface.<p>
 *
 * To implement an unmodifiable collection, the programmer needs only to
 * extend this class and provide implementations for the <tt>iterator</tt> and
 * <tt>size</tt> methods.  (The iterator returned by the <tt>iterator</tt>
 * method must implement <tt>hasNext</tt> and <tt>next</tt>.)<p>
 *
 * To implement a modifiable collection, the programmer must additionally
 * override this class's <tt>add</tt> method (which otherwise throws an
 * <tt>UnsupportedOperationException</tt>), and the iterator returned by the
 * <tt>iterator</tt> method must additionally implement its <tt>remove</tt>
 * method.<p>
 *
 * The programmer should generally provide a void (no argument) and
 * <tt>Collection</tt> constructor, as per the recommendation in the
 * <tt>Collection</tt> interface specification.<p>
 *
 * The documentation for each non-abstract method in this class describes its
 * implementation in detail.  Each of these methods may be overridden if
 * the collection being implemented admits a more efficient implementation.<p>
 */

abstract class AbstractCollection implements Collection {
  use CollectionDefaultMethods;

  /**
   * Sole constructor.  (For invocation by subclass constructors, typically
   * implicit.)
   */
  protected function __construct() {

  }

  // Query Operations

  /**
   * Returns an iterator over the elements contained in this collection.
   *
   * @return  Iterator  An iterator over the elements contained in this collection
   */
  public abstract function iterator(): Iterator;

  /**
   * {@inheritdoc}
   */
  public abstract function size(): int;

  /**
   * {@inheritdoc}
   *
   * <p>This implementation returns <tt>size() == 0</tt>.
   */
  public function isEmpty(): bool {
    return $this->size() == 0;
  }

  /**
   * {@inheritdoc}
   *
   * <p>This implementation iterates over the elements in the collection,
   * checking each element in turn for equality with the specified element.
   *
   * @throws  NullPointerException {@inheritdoc}
   */
  public function contains($value): bool {
    $it = $this->iterator();

    if($value === null) {
      while($it->hasNext()) {
        if($it->next() === null) {
          return true;
        }
      }
    } else {
      while($it->hasNext()) {
        if($value === $it->next()) {
          return true;
        }
      }
    }

    return false;
  }

  /**
   * {@inheritdoc}
   *
   * <p>This implementation returns an array containing all the elements
   * returned by this collection's iterator, in the same order, stored in
   * consecutive elements of the array, starting with index <tt>0</tt>.
   * The length of the returned array is equal to the number of elements
   * returned by the iterator, even if the size of this collection changes
   * during iteration, as might happen if the collection permits
   * concurrent modification during iteration.  The <tt>size</tt> method is
   * called only as an optimization hint; the correct result is returned
   * even if the iterator returns a different number of elements.
   *
   * <p>This method is equivalent to:
   *
   *     $list = new ArrayList($this->size());
   *     foreach($this as $e) {
   *       $list.add($e);
   *     }
   *     return $list->toArray();
   */
  public function toArray(): array {
    $r = [];
    $it = $this->iterator();

    while($it->hasNext()) {
      $r[] = $it->next();
    }

    return $r;
  }

  // Modification Operations

  /**
   * {@inheritdoc}
   *
   * <p>This implementation always throws an
   * <tt>UnsupportedOperationException</tt>.
   *
   * @throws UnsupportedOperationException {@inheritdoc}
   * @throws NullPointerException          {@inheritdoc}
   * @throws IllegalArgumentException      {@inheritdoc}
   * @throws IllegalStateException         {@inheritdoc}
   */
  public function add($value): bool {
    throw new UnsupportedOperationException();
  }

  /**
   * {@inheritdoc}
   *
   * <p>This implementation iterates over the collection looking for the
   * specified element.  If it finds the element, it removes the element
   * from the collection using the iterator's remove method.
   *
   * <p>Note that this implementation throws an
   * <tt>UnsupportedOperationException</tt> if the iterator returned by this
   * collection's iterator method does not implement the <tt>remove</tt>
   * method and this collection contains the specified object.
   *
   * @throws UnsupportedOperationException {@inheritdoc}
   * @throws NullPointerException          {@inheritdoc}
   */
  public function remove($value): bool {
    $it = $this->iterator();

    while($it->hasNext()) {
      if($value === $it->next()) {
        $it->remove();
        return true;
      }
    }

    return false;
  }

  // Bulk Operations

  /**
   * {@inheritdoc}
   *
   * <p>This implementation iterates over the specified collection,
   * checking each element returned by the iterator in turn to see
   * if it's contained in this collection.  If all elements are so
   * contained <tt>true</tt> is returned, otherwise <tt>false</tt>.
   *
   * @throws  NullPointerException  {@inheritdoc}
   */
  public function containsAll(Collection $other): bool {
    foreach($other as $e) {
      if(!$this->contains($e)) {
        return false;
      }
    }

    return true;
  }

  /**
   * {@inheritdoc}
   *
   * <p>This implementation iterates over the specified collection, and adds
   * each object returned by the iterator to this collection, in turn.
   *
   * <p>Note that this implementation will throw an
   * <tt>UnsupportedOperationException</tt> unless <tt>add</tt> is
   * overridden (assuming the specified collection is non-empty).
   *
   * @throws UnsupportedOperationException {@inheritdoc}
   * @throws NullPointerException          {@inheritdoc}
   * @throws IllegalArgumentException      {@inheritdoc}
   * @throws IllegalStateException         {@inheritdoc}
   */
  public function addAll(Collection $other): bool {
    $modified = false;

    foreach($other as $e) {
      if($this->add($e)) {
        $modified = true;
      }
    }

    return $modified;
  }

  /**
   * {@inheritdoc}
   *
   * <p>This implementation iterates over this collection, checking each
   * element returned by the iterator in turn to see if it's contained
   * in the specified collection.  If it's so contained, it's removed from
   * this collection with the iterator's <tt>remove</tt> method.
   *
   * <p>Note that this implementation will throw an
   * <tt>UnsupportedOperationException</tt> if the iterator returned by the
   * <tt>iterator</tt> method does not implement the <tt>remove</tt> method
   * and this collection contains one or more elements in common with the
   * specified collection.
   *
   * @throws UnsupportedOperationException {@inheritdoc}
   * @throws NullPointerException          {@inheritdoc}
   */
  public function removeAll(Collection $other): bool {
    $modified = false;
    $it = $this->iterator();

    while($it->hasNext()) {
      if($other->contains($it->next())) {
        $it->remove();
        $modified = true;
      }
    }

    return $modified;

  }

  /**
   * {@inheritdoc}
   *
   * <p>This implementation iterates over this collection, checking each
   * element returned by the iterator in turn to see if it's contained
   * in the specified collection.  If it's not so contained, it's removed
   * from this collection with the iterator's <tt>remove</tt> method.
   *
   * <p>Note that this implementation will throw an
   * <tt>UnsupportedOperationException</tt> if the iterator returned by the
   * <tt>iterator</tt> method does not implement the <tt>remove</tt> method
   * and this collection contains one or more elements not present in the
   * specified collection.
   *
   * @throws UnsupportedOperationException {@inheritdoc}
   * @throws NullPointerException          {@inheritdoc}
   */
  public function retainAll(Collection $other): bool {
    $modified = false;
    $it = $this->iterator();

    while($it->hasNext()) {
      if(!$other->contains($it->next())) {
        $it->remove();
        $modified = true;
      }
    }

    return $modified;
  }

  /**
   * {@inheritdoc}
   *
   * <p>This implementation iterates over this collection, removing each
   * element using the <tt>Iterator.remove</tt> operation.  Most
   * implementations will probably choose to override this method for
   * efficiency.
   *
   * <p>Note that this implementation will throw an
   * <tt>UnsupportedOperationException</tt> if the iterator returned by this
   * collection's <tt>iterator</tt> method does not implement the
   * <tt>remove</tt> method and this collection is non-empty.
   *
   * @throws UnsupportedOperationException {@inheritdoc}
   */
  public function clear(): void {
    $it = $this->iterator();

    while($it->hasNext()) {
      $it->next();
      $it->remove();
    }
  }

  //  String conversion

  /**
   * Returns a string representation of this collection.  The string
   * representation consists of a list of the collection's elements in the
   * order they are returned by its iterator, enclosed in square brackets
   * (<tt>"[]"</tt>).  Adjacent elements are separated by the characters
   * <tt>", "</tt> (comma and space).  Elements are converted to strings as
   * by {@link String#valueOf(Object)}.
   *
   * @return  string  A string representation of this collection
   *
   * @throws  NoSuchElementException
   */
  public function __toString(): string {
    $it = $this->iterator();

    if(!$it->hasNext()) {
      return '[]';
    }

    $sb = '[';

    for(;;) {
      $e = $it->next();
      $sb .= ($e === $this ? "(this Collection)" : $e);

      if(!$it->hasNext()) {
        return $sb . ']';
      }

      $sb .= ', ';
    }

    return '[]';
  }

  /**
   * @param  mixed  $other
   *
   * @return  bool
   */
  public function equals($other) : bool {
    if(!($other instanceof AbstractCollection)) {
      return false;
    }

    return md5(serialize($this)) === md5(serialize($other));
  }
}
