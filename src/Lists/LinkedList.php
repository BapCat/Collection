<?php declare(strict_types=1); namespace BapCat\Collection\Lists;
use BapCat\Collection\Collection;
use BapCat\Collection\Deque;
use BapCat\Collection\IndexOutOfBoundsException;
use BapCat\Collection\Iterator;
use BapCat\Collection\NoSuchElementException;
use BapCat\Collection\NullPointerException;

/**
 * Doubly-linked list implementation of the {@code List} and {@code Deque}
 * interfaces.  Implements all optional list operations, and permits all
 * elements (including {@code null}).
 *
 * <p>All of the operations perform as could be expected for a doubly-linked
 * list.  Operations that index into the list will traverse the list from
 * the beginning or the end, whichever is closer to the specified index.
 *
 * <p>This class is a member of the
 * <a href="{@docRoot}/../technotes/guides/collections/index.html">
 * Java Collections Framework</a>.
 *
 * @author  Josh Bloch
 * @see     List
 * @see     ArrayList
 * @since 1.2
 * @param <E> the type of elements held in this collection
 */

class LinkedList extends AbstractSequentialList implements ListCollection, Deque {
  /**
   * @var  int
   */
  private $size = 0;

  /**
   * Pointer to first node.
   * Invariant: (first == null && last == null) ||
   *            (first.prev == null && first.item != null)
   *
   * @var  LinkedList©Node
   */
  private $first;

  /**
   * Pointer to last node.
   * Invariant: (first == null && last == null) ||
   *            (last.next == null && last.item != null)
   *
   * @var  LinkedList©Node
   */
  private $last;

  /**
   * Constructs a list optional containing the elements of the specified
   * collection, in the order they are returned by the collection's iterator.
   *
   * @param  Collection|null  $c  The collection whose elements are to be placed into this list
   */
  public function __construct(?Collection $c = null) {
    if($c !== null) {
      $this->addAll($c);
    }
  }

  /**
   * Links e as first element.
   *
   * @param  LinkedList©Node  $e
   *
   * @return  void
   */
  private function linkFirst($e): void {
    $f = $this->first;
    $newNode = new LinkedList©Node(null, $e, $f);
    $this->first = $newNode;

    if($f === null) {
      $this->last = $newNode;
    } else {
      $f->prev = $newNode;
    }

    $this->size++;
  }

  /**
   * Links e as last element.
   *
   * @param  LinkedList©Node  $e
   *
   * @return  void
   */
  private function linkLast($e): void {
    $l = $this->last;
    $newNode = new LinkedList©Node($l, $e, null);
    $this->last = $newNode;

    if($l === null) {
      $this->first = $newNode;
    } else {
      $l->next = $newNode;
    }

    $this->size++;
  }

  /**
   * Inserts element e before non-null Node succ.
   *
   * @param  mixed  $e
   * @param  LinkedList©Node   $succ
   *
   * @return  void
   */
  private function linkBefore($e, LinkedList©Node $succ): void {
    $pred = $succ->prev;
    $newNode = new LinkedList©Node($pred, $e, $succ);
    $succ->prev = $newNode;

    if($pred === null) {
      $this->first = $newNode;
    } else {
      $pred->next = $newNode;
    }

    $this->size++;
  }

  /**
   * Unlinks non-null first node f.
   *
   * @param  LinkedList©Node  $f
   *
   * @return  mixed
   */
  private function unlinkFirst(LinkedList©Node $f) {
    $element = $f->item;
    $next = $f->next;
    $f->item = null;
    $f->next = null; // help GC
    $this->first = $next;

    if($next === null) {
      $this->last = null;
    } else {
      $next->prev = null;
    }

    $this->size--;
    return $element;
  }

  /**
   * Unlinks non-null last node l.
   *
   * @param  LinkedList©Node  $l
   *
   * @return  mixed
   */
  private function unlinkLast(LinkedList©Node $l) {
    $element = $l->item;
    $prev = $l->prev;
    $l->item = null;
    $l->prev = null; // help GC
    $this->last = $prev;

    if($prev === null) {
      $this->first = null;
    } else {
      $prev->next = null;
    }

    $this->size--;
    return $element;
  }

  /**
   * Unlinks non-null node x.
   *
   * @param  LinkedList©Node  $x
   *
   * @return  mixed
   */
  private function unlink(LinkedList©Node $x) {
    $element = $x->item;
    $next = $x->next;
    $prev = $x->prev;

    if($prev === null) {
      $this->first = $next;
    } else {
      $prev->next = $next;
      $x->prev = null;
    }

    if($next === null) {
      $this->last = $prev;
    } else {
      $next->prev = $prev;
      $x->next = null;
    }

    $x->item = null;
    $this->size--;
    return $element;
  }

  /**
   * Returns the first element in this list.
   *
   * @return  mixed  The first element in this list
   *
   * @throws NoSuchElementException if this list is empty
   */
  public function getFirst() {
    $f = $this->first;
    if($f === null) {
      throw new NoSuchElementException();
    }

    return $f->item;
  }

  /**
   * Returns the last element in this list.
   *
   * @return  mixed  The last element in this list
   *
   * @throws NoSuchElementException if this list is empty
   */
  public function getLast() {
    $l = $this->last;

    if($l === null) {
      throw new NoSuchElementException();
    }

    return $l->item;
  }

  /**
   * Removes and returns the first element from this list.
   *
   * @return  mixed  The first element from this list
   *
   * @throws NoSuchElementException if this list is empty
   */
  public function removeFirst() {
    $f = $this->first;

    if($f === null) {
      throw new NoSuchElementException();
    }

    return $this->unlinkFirst($f);
  }

  /**
   * Removes and returns the last element from this list.
   *
   * @return  mixed  The last element from this list
   *
   * @throws NoSuchElementException if this list is empty
   */
  public function removeLast() {
    $l = $this->last;

    if($l === null) {
      throw new NoSuchElementException();
    }

    return $this->unlinkLast($l);
  }

  /**
   * Inserts the specified element at the beginning of this list.
   *
   * @param  mixed  $e  The element to add
   *
   * @return  void
   */
  public function addFirst($e): void {
    $this->linkFirst($e);
  }

  /**
   * Appends the specified element to the end of this list.
   *
   * <p>This method is equivalent to {@link #add}.
   *
   * @param  mixed  $e  The element to add
   *
   * @return  void
   */
  public function addLast($e): void {
    $this->linkLast($e);
  }

  /**
   * Returns {@code true} if this list contains the specified element.
   * More formally, returns {@code true} if and only if this list contains
   * at least one element {@code e} such that
   * <tt>(o==null&nbsp;?&nbsp;e==null&nbsp;:&nbsp;o.equals(e))</tt>.
   *
   * @param  mixed  $o  Element whose presence in this list is to be tested
   *
   * @return  bool  {@code true} if this list contains the specified element
   */
  public function contains($o): bool {
    return $this->indexOf($o) !== -1;
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
   * Appends the specified element to the end of this list.
   *
   * <p>This method is equivalent to {@link #addLast}.
   *
   * @param  mixed  $e  Element to be appended to this list
   *
   * @return  bool  {@code true} (as specified by {@link Collection#add})
   */
  public function add($e): bool {
    $this->linkLast($e);
    return true;
  }

  /**
   * Removes the first occurrence of the specified element from this list,
   * if it is present.  If this list does not contain the element, it is
   * unchanged.  More formally, removes the element with the lowest index
   * {@code i} such that
   * <tt>(o==null&nbsp;?&nbsp;get(i)==null&nbsp;:&nbsp;o.equals(get(i)))</tt>
   * (if such an element exists).  Returns {@code true} if this list
   * contained the specified element (or equivalently, if this list
   * changed as a result of the call).
   *
   * @param  mixed  $o  Element to be removed from this list, if present
   *
   * @return  bool  {@code true} if this list contained the specified element
   */
  public function remove($o): bool {
    for($x = $this->first; $x !== null; $x = $x->next) {
      if($o === $x->item) {
        $this->unlink($x);
        return true;
      }
    }

    return false;
  }

  /**
   * Appends all of the elements in the specified collection to the end of
   * this list, in the order that they are returned by the specified
   * collection's iterator.  The behavior of this operation is undefined if
   * the specified collection is modified while the operation is in
   * progress.  (Note that this will occur if the specified collection is
   * this list, and it's nonempty.)
   *
   * @param  Collection  $c  Collection containing elements to be added to this list
   *
   * @return  bool  {@code true} if this list changed as a result of the call
   *
   * @throws NullPointerException if the specified collection is null
   */
  public function addAll(Collection $c): bool {
    return $this->addAllAt($this->size, $c);
  }

  /**
   * Inserts all of the elements in the specified collection into this
   * list, starting at the specified position.  Shifts the element
   * currently at that position (if any) and any subsequent elements to
   * the right (increases their indices).  The new elements will appear
   * in the list in the order that they are returned by the
   * specified collection's iterator.
   *
   * @param  int  $index     The ndex at which to insert the first element
   *                         from the specified collection
   * @param  Collection  $c  A collection containing elements to be added to this list
   *
   * @return  bool  {@code true} if this list changed as a result of the call
   *
   * @throws IndexOutOfBoundsException {@inheritdoc}
   * @throws NullPointerException if the specified collection is null
   */
  public function addAllAt(int $index, Collection $c): bool {
    $this->checkPositionIndex($index);

    $a = $c->toArray();
    $numNew = count($a);
    if($numNew === 0) {
      return false;
    }

    if($index === $this->size) {
      $succ = null;
      $pred = $this->last;
    } else {
      $succ = $this->node($index);
      $pred = $succ->prev;
    }

    foreach($a as $e) {
      $newNode = new LinkedList©Node($pred, $e, null);

      if($pred === null) {
        $this->first = $newNode;
      } else {
        $pred->next = $newNode;
      }

      $pred = $newNode;
    }

    if($succ === null) {
      $this->last = $pred;
    } else {
      $pred->next = $succ;
      $succ->prev = $pred;
    }

    $this->size += $numNew;
    return true;
  }

  /**
   * Removes all of the elements from this list.
   * The list will be empty after this call returns.
   *
   * @return  void
   */
  public function clear(): void {
    // Clearing all of the links between nodes is "unnecessary", but:
    // - helps a generational GC if the discarded nodes inhabit
    //   more than one generation
    // - is sure to free memory even if there is a reachable Iterator
    for($x = $this->first; $x !== null; ) {
      $next = $x->next;
      $x->item = null;
      $x->next = null;
      $x->prev = null;
      $x = $next;
    }

    $this->first = $this->last = null;
    $this->size = 0;
  }


  // Positional Access Operations

  /**
   * Returns the element at the specified position in this list.
   *
   * @param  int  $index  Index of the element to return
   *
   * @return  mixed  The element at the specified position in this list
   *
   * @throws IndexOutOfBoundsException {@inheritDoc}
   */
  public function get(int $index) {
    $this->checkElementIndex($index);
    return $this->node($index)->item;
  }

  /**
   * Replaces the element at the specified position in this list with the
   * specified element.
   *
   * @param  int    $index    The index of the element to replace
   * @param  mixed  $element  Element to be stored at the specified position
   *
   * @return  mixed  The element previously at the specified position
   *
   * @throws IndexOutOfBoundsException {@inheritdoc}
   */
  public function set(int $index, $element) {
    $this->checkElementIndex($index);
    $x = $this->node($index);
    $oldVal = $x->item;
    $x->item = $element;
    return $oldVal;
  }

  /**
   * Inserts the specified element at the specified position in this list.
   * Shifts the element currently at that position (if any) and any
   * subsequent elements to the right (adds one to their indices).
   *
   * @param  int    $index    The index at which the specified element is to be inserted
   * @param  mixed  $element  The element to be inserted
   *
   * @return  void
   *
   * @throws IndexOutOfBoundsException {@inheritDoc}
   */
  public function addAt(int $index, $element): void {
    $this->checkPositionIndex($index);

    if($index === $this->size) {
      $this->linkLast($element);
    } else {
      $this->linkBefore($element, $this->node($index));
    }
  }

  /**
   * Removes the element at the specified position in this list.  Shifts any
   * subsequent elements to the left (subtracts one from their indices).
   * Returns the element that was removed from the list.
   *
   * @param  int  $index  The index of the element to be removed
   *
   * @return  mixed  The element previously at the specified position
   *
   * @throws IndexOutOfBoundsException {@inheritdoc}
   */
  public function removeAt(int $index) {
    $this->checkElementIndex($index);
    return $this->unlink($this->node($index));
  }

  /**
   * Tells if the argument is the index of an existing element.
   *
   * @param  int  $index
   *
   * @return  bool
   */
  private function isElementIndex(int $index): bool {
    return $index >= 0 && $index < $this->size;
  }

  /**
   * Tells if the argument is the index of a valid position for an
   * iterator or an add operation.
   *
   * @param  int  $index
   *
   * @return  bool
   */
  private function isPositionIndex(int $index): bool {
    return $index >= 0 && $index <= $this->size;
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
   * @param  int  $index
   *
   * @return  void
   */
  private function checkElementIndex(int $index): void {
    if(!$this->isElementIndex($index)) {
      throw new IndexOutOfBoundsException($this->outOfBoundsMsg($index));
    }
  }

  /**
   * @param  int  $index
   *
   * @return  void
   */
  private function checkPositionIndex(int $index): void {
    if(!$this->isPositionIndex($index)) {
      throw new IndexOutOfBoundsException($this->outOfBoundsMsg($index));
    }
  }

  /**
   * Returns the (non-null) Node at the specified element index.
   *
   * @param  int  $index
   *
   * @return  LinkedList©Node
   */
  private function node(int $index): LinkedList©Node {
    if($index < ($this->size >> 1)) {
      $x = $this->first;

      for($i = 0; $i < $index; $i++) {
        $x = $x->next;
      }

      return $x;
    } else {
      $x = $this->last;

      for($i = $this->size - 1; $i > $index; $i--) {
        $x = $x->prev;
      }

      return $x;
    }
  }

  // Search Operations

  /**
   * Returns the index of the first occurrence of the specified element
   * in this list, or -1 if this list does not contain the element.
   * More formally, returns the lowest index {@code i} such that
   * <tt>(o==null&nbsp;?&nbsp;get(i)==null&nbsp;:&nbsp;o.equals(get(i)))</tt>,
   * or -1 if there is no such index.
   *
   * @param  mixed  $o  The element to search for
   *
   * @return  int  The index of the first occurrence of the specified element in
   *               this list, or -1 if this list does not contain the element
   */
  public function indexOf($o): int {
    $index = 0;

    for($x = $this->first; $x !== null; $x = $x->next) {
      if($x->item === $o) {
        return $index;
      }

      $index++;
    }

    return -1;
  }

  /**
   * Returns the index of the last occurrence of the specified element
   * in this list, or -1 if this list does not contain the element.
   * More formally, returns the highest index {@code i} such that
   * <tt>(o==null&nbsp;?&nbsp;get(i)==null&nbsp;:&nbsp;o.equals(get(i)))</tt>,
   * or -1 if there is no such index.
   *
   * @param  mixed  $o  The element to search for
   *
   * @return  int  The index of the last occurrence of the specified element in
   *               this list, or -1 if this list does not contain the element
   */
  public function lastIndexOf($o): int {
    $index = $this->size;

    for($x = $this->last; $x !== null; $x = $x->prev) {
      $index--;

      if($x->item === $o) {
        return $index;
      }
    }

    return -1;
  }

  // Queue operations.

  /**
   * Retrieves, but does not remove, the head (first element) of this list.
   *
   * @return  mixed  The head of this list, or {@code null} if this list is empty
   */
  public function peek() {
    $f = $this->first;
    return ($f === null) ? null : $f->item;
  }

  /**
   * Retrieves, but does not remove, the head (first element) of this list.
   *
   * @return  mixed  The head of this list
   *
   * @throws NoSuchElementException if this list is empty
   */
  public function element() {
    return $this->getFirst();
  }

  /**
   * Retrieves and removes the head (first element) of this list.
   *
   * @return  mixed  The head of this list, or {@code null} if this list is empty
   */
  public function poll() {
    $f = $this->first;
    return ($f === null) ? null : $this->unlinkFirst($f);
  }

  /**
   * Retrieves and removes the head (first element) of this list.
   *
   * @return  mixed  The head of this list
   *
   * @throws NoSuchElementException if this list is empty
   */
  public function pop() {
    return $this->removeFirst();
  }

  /**
   * Adds the specified element as the tail (last element) of this list.
   *
   * @param  mixed  $e  The element to add
   *
   * @return  bool  {@code true} (as specified by {@link Queue::offer})
   */
  public function offer($e): bool {
    return $this->add($e);
  }

  // Deque operations
  /**
   * Inserts the specified element at the front of this list.
   *
   * @param  mixed  $e  The element to insert
   *
   * @return  bool  {@code true} (as specified by {@link Deque#offerFirst})
   */
  public function offerFirst($e): bool {
    $this->addFirst($e);
    return true;
  }

  /**
   * Inserts the specified element at the end of this list.
   *
   * @param  mixed  $e  The element to insert
   *
   * @return  bool  {@code true} (as specified by {@link Deque#offerLast})
   */
  public function offerLast($e): bool {
    $this->addLast($e);
    return true;
  }

  /**
   * Retrieves, but does not remove, the first element of this list,
   * or returns {@code null} if this list is empty.
   *
   * @return  mixed  The first element of this list, or {@code null}
   *                 if this list is empty
   */
  public function peekFirst() {
      $f = $this->first;
      return ($f === null) ? null : $f->item;
   }

  /**
   * Retrieves, but does not remove, the last element of this list,
   * or returns {@code null} if this list is empty.
   *
   * @return  mixed  The last element of this list, or {@code null}
   *                 if this list is empty
   */
  public function peekLast() {
    $l = $this->last;
    return ($l === null) ? null : $l->item;
  }

  /**
   * Retrieves and removes the first element of this list,
   * or returns {@code null} if this list is empty.
   *
   * @return  mixed  The first element of this list, or {@code null} if
   *                 this list is empty
   */
  public function pollFirst() {
    $f = $this->first;
    return ($f === null) ? null : $this->unlinkFirst($f);
  }

  /**
   * Retrieves and removes the last element of this list,
   * or returns {@code null} if this list is empty.
   *
   * @return  mixed  The last element of this list, or {@code null} if
   *                 this list is empty
   */
  public function pollLast() {
    $l = $this->last;
    return ($l === null) ? null : $this->unlinkLast($l);
  }

  /**
   * Pushes an element onto the stack represented by this list.  In other
   * words, inserts the element at the front of this list.
   *
   * <p>This method is equivalent to {@link #addFirst}.
   *
   * @param  mixed  $e  The element to push
   *
   * @return  void
   */
  public function push($e): void {
    $this->addFirst($e);
  }

  /**
   * Removes the first occurrence of the specified element in this
   * list (when traversing the list from head to tail).  If the list
   * does not contain the element, it is unchanged.
   *
   * @param  mixed  $o  The element to be removed from this list, if present
   *
   * @return  bool  {@code true} if the list contained the specified element
   */
  public function removeFirstOccurrence($o): bool {
    return $this->remove($o);
  }

  /**
   * Removes the last occurrence of the specified element in this
   * list (when traversing the list from head to tail).  If the list
   * does not contain the element, it is unchanged.
   *
   * @param  mixed  $o  The element to be removed from this list, if present
   *
   * @return  bool  {@code true} if the list contained the specified element
   */
  public function removeLastOccurrence($o): bool {
    for($x = $this->last; $x !== null; $x = $x->prev) {
      if($x->item === $o) {
        $this->unlink($x);
        return true;
      }
    }

    return false;
  }

  /**
   * Returns a list-iterator of the elements in this list (in proper
   * sequence), starting at the specified position in the list.
   * Obeys the general contract of {@code List.listIterator(int)}.<p>
   *
   * The list-iterator is <i>fail-fast</i>: if the list is structurally
   * modified at any time after the Iterator is created, in any way except
   * through the list-iterator's own {@code remove} or {@code add}
   * methods, the list-iterator will throw a
   * {@code ConcurrentModificationException}.  Thus, in the face of
   * concurrent modification, the iterator fails quickly and cleanly, rather
   * than risking arbitrary, non-deterministic behavior at an undetermined
   * time in the future.
   *
   * @param  int  $index  The index of the first element to be returned from the
   *                      list-iterator (by a call to {@code next})
   *
   * @return  ListIterator  A ListIterator of the elements in this list (in proper
   *                        sequence), starting at the specified position in the list
   *
   * @throws IndexOutOfBoundsException {@inheritdoc}
   */
  public function listIterator(int $index = 0): ListIterator {
    $this->checkPositionIndex($index);
    return new LinkedList©ListItr($this, function(int $index): LinkedList©Node {
      return $this->node($index);
    }, function($e, LinkedList©Node $node): void {
      $this->linkBefore($e, $node);
    }, function($e): void {
      $this->linkLast($e);
    }, function(LinkedList©Node $node) {
      return $this->unlink($node);
    }, $this->last, $index);
  }

  /**
   * @return  Iterator
   */
  public function descendingIterator(): Iterator {
    return new LinkedList©DescendingIterator($this, function(int $index): LinkedList©Node {
      return $this->node($index);
    }, function($e, LinkedList©Node $node): void {
      $this->linkBefore($e, $node);
    }, function($e): void {
      $this->linkLast($e);
    }, function(LinkedList©Node $node) {
      return $this->unlink($node);
    }, $this->last, $this->size);
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
   * @return  mixed[]  An array containing all of the elements in this list
   *                   in proper sequence
   */
  public function toArray(): array {
    $i = 0;
    $result = [];

    for($x = $this->first; $x !== null; $x = $x->next) {
      $result[$i++] = $x->item;
    }

    return $result;
  }
}
