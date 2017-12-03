<?php declare(strict_types=1); namespace BapCat\Collection\Lists;

use BapCat\Collection\Collection;
use BapCat\Collection\Comparator;
use BapCat\Collection\Functions\UnaryOperator;
use BapCat\Collection\IllegalArgumentException;
use BapCat\Collection\IndexOutOfBoundsException;
use BapCat\Collection\NullPointerException;
use BapCat\Collection\UnsupportedOperationException;

/**
 * An ordered collection (also known as a <i>sequence</i>).  The user of this
 * interface has precise control over where in the list each element is
 * inserted.  The user can access elements by their integer index (position in
 * the list), and search for elements in the list.<p>
 *
 * Unlike sets, lists typically allow duplicate element, and they typically
 * allow multiple null elements if they allow null elements at all.  It is not
 * inconceivable that someone might wish to implement a list that prohibits
 * duplicates, by throwing runtime exceptions when the user attempts to insert
 * them, but we expect this usage to be rare.<p>
 *
 * The <tt>List</tt> interface places additional stipulations, beyond those
 * specified in the <tt>Collection</tt> interface, on the contracts of the
 * <tt>iterator</tt>, <tt>add</tt>, <tt>remove</tt>, and <tt>equals</tt> methods.
 * Declarations for other inherited methods are also included here for convenience.<p>
 *
 * The <tt>List</tt> interface provides four methods for positional (indexed)
 * access to list elements.  Lists (like Java arrays) are zero based.  Note
 * that these operations may execute in time proportional to the index value
 * for some implementations (the <tt>LinkedList</tt> class, for
 * example). Thus, iterating over the elements in a list is typically
 * preferable to indexing through it if the caller does not know the
 * implementation.<p>
 *
 * The <tt>List</tt> interface provides a special iterator, called a
 * <tt>ListIterator</tt>, that allows element insertion and replacement, and
 * bidirectional access in addition to the normal operations that the
 * <tt>Iterator</tt> interface provides.  A method is provided to obtain a
 * list iterator that starts at a specified position in the list.<p>
 *
 * The <tt>List</tt> interface provides two methods to search for a specified
 * object.  From a performance standpoint, these methods should be used with
 * caution.  In many implementations they will perform costly linear
 * searches.<p>
 *
 * The <tt>List</tt> interface provides two methods to efficiently insert and
 * remove multiple elements at an arbitrary point in the list.<p>
 *
 * Note: While it is permissible for lists to contain themselves as elements,
 * extreme caution is advised: the <tt>equals</tt> method is no longer well
 * defined on such a list.
 *
 * <p>Some list implementations have restrictions on the elements that
 * they may contain.  For example, some implementations prohibit null elements,
 * and some have restrictions on the types of their elements.  Attempting to
 * add an ineligible element throws an unchecked exception, typically
 * <tt>NullPointerException</tt>.  Attempting to query the presence of an
 * ineligible element may throw an exception, or it may simply return false;
 * some implementations will exhibit the former behavior and some will
 * exhibit the latter.  More generally, attempting an operation on an
 * ineligible element whose completion would not result in the insertion of
 * an ineligible element into the list may throw an exception or it may
 * succeed, at the option of the implementation.
 */
interface ListCollection extends Collection {
  /**
   * Inserts all of the elements in the specified collection into this list at the specified position (optional operation).
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
   * @throws  UnsupportedOperationException if the <tt>addAll</tt> operation is not supported by this list
   * @throws  NullPointerException if the specified collection contains one or more null elements and this list does
   *          not permit null elements, or if the specified collection is null
   * @throws  IllegalArgumentException if some property of an element of the
   *          specified collection prevents it from being added to this list
   * @throws  IndexOutOfBoundsException if the index is out of range (<tt>index < 0 || index > size()</tt>)
   */
  function addAllAt(int $index, Collection $other) : bool;

  /**
   * Returns the element at the specified position in this list.
   *
   * @param  int  $index  The index of the element to return
   *
   * @return  mixed  The element at the specified position in this list
   *
   * @throws  IndexOutOfBoundsException if the index is out of range
   *          (<tt>index < 0 || index > size()</tt>)
   */
  function get(int $index);

  /**
   * Replaces the element at the specified position in this list with the
   * specified element (optional operation).
   *
   * @param  int    $index  The index of the element to replace
   * @param  mixed  $value  The element to be stored at the specified position
   *
   * @return  mixed  The element previously at the specified position
   *
   * @throws  UnsupportedOperationException if the <tt>set<tt> operation
   *          is not supported by this list
   * @throws  NullPointerException if the specified element is null and
   *          this list does not permit null elements
   * @throws  IllegalArgumentException if some property of the specified
   *          element prevents it from being added to this list
   * @throws  IndexOutOfBoundsException if the index is out of range
   *          (<tt>index < 0 || index > size()</tt>)
   */
  function set(int $index, $value);

  /**
   * Inserts the specified element at the specified position in this list
   * (optional operation).  Shifts the element currently at that position
   * (if any) and any subsequent elements to the right (adds one to their
   * indices).
   *
   * @param  int    $index  The index at which the specified element is to be inserted
   * @param  mixed  $value  The element to be inserted
   *
   * @throws  UnsupportedOperationException if the <tt>add</tt> operation
   *          is not supported by this list
   * @throws  NullPointerException if the specified element is null and
   *          this list does not permit null elements
   * @throws  IllegalArgumentException if some property of the specified
   *          element prevents it from being added to this list
   * @throws  IndexOutOfBoundsException if the index is out of range
   *          (<tt>index < 0 || index > size()</tt>)
   */
  function addAt(int $index, $value): void;

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
   * @throws  UnsupportedOperationException if the <tt>remove</tt> operation
   *          is not supported by this list
   * @throws  IndexOutOfBoundsException if the index is out of range
   *          (<tt>index < 0 || index > size()</tt>)
   */
  function removeAt(int $index);

  /**
   * Replaces each element of this list with the result of applying the
   * operator to that element.  Errors or runtime exceptions thrown by
   * the operator are relayed to the caller.
   *
   * The default implementation is equivalent to, for this <tt>list</tt>:
   *
   *     final ListIterator<E> li = list.listIterator();
   *     while (li.hasNext()) {
   *         li.set(operator.apply(li.next()));
   *     }
   *
   *
   * If the list's list-iterator does not support the <tt>set</tt> operation
   * then an <tt>UnsupportedOperationException</tt> will be thrown when
   * replacing the first element.
   *
   * @param  UnaryOperator  $operator  The operator to apply to each element
   *
   * @return  void
   *
   * @throws  UnsupportedOperationException if this list is unmodifiable.
   *          Implementations may throw this exception if an element
   *          cannot be replaced or if, in general, modification is not
   *          supported
   * @throws  NullPointerException if the specified operator is null or
   *          if the operator result is a null value and this list does
   *          not permit null elements
   */
  function replaceAll(UnaryOperator $operator): void;

  /**
   * Sorts this list according to the order induced by the specified
   * {@link Comparator}.
   *
   * <p>All elements in this list must be <i>mutually comparable</i> using the
   * specified comparator (that is, <tt>c.compare(e1, e2)</tt> must not throw
   * a <tt>ClassCastException</tt> for any elements <tt>e1</tt> and <tt>e2</tt>
   * in the list).
   *
   * <p>If the specified comparator is <tt>null</tt> then all elements in this
   * list must implement the <tt>Comparable</tt> interface and the elements'
   * Comparable natural ordering should be used.
   *
   * <p>This list must be modifiable, but need not be resizable.
   *
   * The default implementation obtains an array containing all elements in
   * this list, sorts the array, and iterates over this list resetting each
   * element from the corresponding position in the array. (This avoids the
   * n<sup>2</sup> log(n) performance that would result from attempting
   * to sort a linked list in place.)
   *
   * This implementation is a stable, adaptive, iterative mergesort that
   * requires far fewer than n lg(n) comparisons when the input array is
   * partially sorted, while offering the performance of a traditional
   * mergesort when the input array is randomly ordered.  If the input array
   * is nearly sorted, the implementation requires approximately n
   * comparisons.  Temporary storage requirements vary from a small constant
   * for nearly sorted input arrays to n/2 object references for randomly
   * ordered input arrays.
   *
   * <p>The implementation takes equal advantage of ascending and
   * descending order in its input array, and can take advantage of
   * ascending and descending order in different parts of the same
   * input array.  It is well-suited to merging two or more sorted arrays:
   * simply concatenate the arrays and sort the resulting array.
   *
   * <p>The implementation was adapted from Tim Peters's list sort for Python
   * (<a href="http://svn.python.org/projects/python/trunk/Objects/listsort.txt">
   * TimSort</a>).  It uses techniques from Peter McIlroy's "Optimistic
   * Sorting and Information Theoretic Complexity", in Proceedings of the
   * Fourth Annual ACM-SIAM Symposium on Discrete Algorithms, pp 467-474,
   * January 1993.
   *
   * @param  Comparator  $comparator  The <tt>Comparator</tt> used to compare list elements.
   *         A <tt>null</tt> value indicates that the elements'
   *         Comparable natural ordering should be used
   *
   * @throws UnsupportedOperationException if the list's list-iterator does
   *         not support the <tt>set</tt> operation
   * @throws IllegalArgumentException
   *         if the comparator is found to violate the {@link Comparator}
   *         contract
   */
  function sort(?Comparator $comparator): void;

  // Search Operations

  /**
   * Returns the index of the first occurrence of the specified element
   * in this list, or -1 if this list does not contain the element.
   *
   * @param  mixed  $value  The element to search for
   *
   * @return  int  The index of the first occurrence of the specified element in
   *               this list, or -1 if this list does not contain the element
   *
   * @throws  NullPointerException if the specified element is null and this
   *          list does not permit null elements
   */
  function indexOf($value) : int;

  /**
   * Returns the index of the last occurrence of the specified element
   * in this list, or -1 if this list does not contain the element.
   *
   * @param  mixed  $value  The element to search for
   *
   * @return  int  The index of the last occurrence of the specified element in
   *               this list, or -1 if this list does not contain the element
   *
   * @throws  NullPointerException if the specified element is null and this
   *          list does not permit null elements
   */
  function lastIndexOf($value) : int;

  // List Iterators

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
   * @throws  IndexOutOfBoundsException if the index is out of range
   *          (<tt>index < 0 || index > size()</tt>)
   */
  function listIterator(int $index = 0): ListIterator;

  // View

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
  function subList(int $fromIndex, int $toIndex): ListCollection;
}
