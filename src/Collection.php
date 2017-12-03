<?php declare(strict_types=1); namespace BapCat\Collection;

use IteratorAggregate;

/**
 * The root collection interface.  This is based heavily on the Java Collections API.
 */
interface Collection extends IteratorAggregate {
  /**
   * Returns the number of elements in this collection
   *
   * @return  int  The number of elements in this collection
   */
  function size(): int;

  /**
   * Returns <tt>true</tt> if this collection contains no elements.
   *
   * @return  bool
   */
  function isEmpty(): bool;

  /**
   * Returns <tt>true</tt> if this collection contains the specified element.
   *
   * @param  mixed  $value  The element whose presence in this collection is to be tested
   *
   * @return  bool  <tt>true</tt> if this collection contains the specified element
   */
  function contains($value): bool;

  /**
   * Returns an iterator over the elements in this collection.  There are no guarantees concerning the order in which
   * the elements are returned (unless this collection is an instance of some class that provides a guarantee).
   *
   * @return  Iterator  An <tt>Iterator</tt> over the elements in this collection
   */
  function iterator(): Iterator;

  /**
   * Returns an array containing all of the elements in this collection. If this collection makes any guarantees as
   * to what order its elements are returned by its iterator, this method must return the elements in the same order.
   *
   * <p>The returned array will be "safe" in that no references to it are maintained by this collection.  (In other
   * words, this method must allocate a new array even if this collection is backed by an array). The caller is thus
   * free to modify the returned array.
   *
   * <p>This method acts as a bridge between array-based and collection-based APIs.
   *
   * @return  mixed[]  an array containing all of the elements in this collection
   */
  function toArray(): array;

  // Modification Operations

  /**
   * Ensures that this collection contains the specified element (optional operation).  Returns <tt>true</tt> if this
   * collection changed as a result of the call.  (Returns <tt>false</tt> if this collection does not permit duplicates
   * and already contains the specified element.)<p>
   *
   * Collections that support this operation may place limitations on what elements may be added to this collection.
   * In particular, some collections will refuse to add <tt>null</tt> elements, and others will impose restrictions on the
   * type of elements that may be added.  Collection classes should clearly specify in their documentation any
   * restrictions on what elements may be added.<p>
   *
   * If a collection refuses to add a particular element for any reason other than that it already contains the
   * element, it <i>must</i> throw an exception (rather than returning <tt>false</tt>).  This preserves the invariant
   * that a collection always contains the specified element after this call returns.
   *
   * @param  mixed  $value  The element whose presence in this collection is to be ensured
   *
   * @return  bool  <tt>true</tt> if this collection changed as a result of the call
   *
   * @throws  UnsupportedOperationException  If the <tt>add</tt> operation is not supported by this collection
   * @throws  NullPointerException           If the specified element is null and this collection does not permit null elements
   * @throws  IllegalArgumentException       If some property of the element prevents it from being added to this collection
   * @throws  IllegalStateException          If the element cannot be added at this time due to insertion restrictions
   */
  function add($value): bool;

  /**
   * Removes a single instance of the specified element from this
   * collection, if it is present (optional operation).  Returns
   * <tt>true</tt> if this collection contained the specified element (or
   * equivalently, if this collection changed as a result of the call).
   *
   * @param  mixed  $value  The element to be removed from this collection, if present
   *
   * @return  bool  <tt>true</tt> if an element was removed as a result of this call
   *
   * @throws  NullPointerException           If the specified element is null and this collection does not permit null elements
   * @throws  UnsupportedOperationException  If the <tt>remove</tt> operation is not supported by this collection
   */
  function remove($value): bool;

  // Bulk Operations

  /**
   * Returns <tt>true</tt> if this collection contains all of the elements in the specified collection.
   *
   * @param  Collection  $other  A collection to be checked for containment in this collection
   *
   * @return  bool  <tt>true</tt> if this collection contains all of the elements in the specified collection
   *
   * @throws  NullPointerException if the specified collection contains one or more null elements and this collection
   *          does not permit null elements or if the specified collection is null.
   */
  function containsAll(Collection $other): bool;

  /**
   * Adds all of the elements in the specified collection to this collection (optional operation).  The behavior of
   * this operation is undefined if the specified collection is modified while the operation is in progress.
   * (This implies that the behavior of this call is undefined if the specified collection is this collection, and
   * this collection is nonempty.)
   *
   * @param  Collection  $other  A collection containing elements to be added to this collection
   *
   * @return  bool  <tt>true</tt> if this collection changed as a result of the call
   *
   * @throws  UnsupportedOperationException if the <tt>addAll</tt> operation is not supported by this collection
   * @throws  NullPointerException if the specified collection contains a null element and this collection does
   *          not permit null elements, or if the specified collection is null
   * @throws  IllegalArgumentException if some property of an element of the specified collection prevents it from
   *          being added to this collection
   * @throws  IllegalStateException if not all the elements can be added at this time due to insertion restrictions
   */
  function addAll(Collection $other): bool;

  /**
   * Removes all of this collection's elements that are also contained in the specified collection (optional operation).
   * After this call returns, this collection will contain no elements in common with the specified collection.
   *
   * @param  Collection  $other A collection containing elements to be removed from this collection
   *
   * @return  bool  <tt>true</tt> if this collection changed as a result of the call
   *
   * @throws  UnsupportedOperationException if the <tt>removeAll</tt> method is not supported by this collection
   * @throws  NullPointerException if this collection contains one or more null elements and the specified collection
   *          does not support null elements
   */
  function removeAll(Collection $other): bool;

  /**
   * Retains only the elements in this collection that are contained in the specified collection (optional operation).
   * In other words, removes from this collection all of its elements that are not contained in the specified collection.
   *
   * @param  Collection  $other  A collection containing elements to be retained in this collection
   *
   * @return  bool  <tt>true</tt> if this collection changed as a result of the call
   *
   * @throws  UnsupportedOperationException if the <tt>retainAll</tt> operation is not supported by this collection
   * @throws  NullPointerException if this collection contains one or more null elements and the specified collection
   *          does not permit null elements
   */
  function retainAll(Collection $other): bool;

  /**
   * Removes all of the elements from this collection (optional operation).
   * The collection will be empty after this method returns.
   *
   * @throws  UnsupportedOperationException if the <tt>clear</tt> operation is not supported by this collection
   */
  function clear(): void;

  // Comparison and hashing

  /**
   * Compares the specified object with this collection for equality. <p>
   *
   * While the <tt>Collection</tt> interface adds no stipulations to the
   * general contract for the <tt>Object.equals</tt>, programmers who
   * implement the <tt>Collection</tt> interface "directly" (in other words,
   * create a class that is a <tt>Collection</tt> but is not a <tt>Set</tt>
   * or a <tt>List</tt>) must exercise care if they choose to override the
   * <tt>Object.equals</tt>.  It is not necessary to do so, and the simplest
   * course of action is to rely on <tt>Object</tt>'s implementation, but
   * the implementor may wish to implement a "value comparison" in place of
   * the default "reference comparison."  (The <tt>List</tt> and
   * <tt>Set</tt> interfaces mandate such value comparisons.)<p>
   *
   * The general contract for the <tt>Object.equals</tt> method states that
   * equals must be symmetric (in other words, <tt>a.equals(b)</tt> if and
   * only if <tt>b.equals(a)</tt>).  The contracts for <tt>List.equals</tt>
   * and <tt>Set.equals</tt> state that lists are only equal to other lists,
   * and sets to other sets.  Thus, a custom <tt>equals</tt> method for a
   * collection class that implements neither the <tt>List</tt> nor
   * <tt>Set</tt> interface must return <tt>false</tt> when this collection
   * is compared to any list or set.  (By the same logic, it is not possible
   * to write a class that correctly implements both the <tt>Set</tt> and
   * <tt>List</tt> interfaces.)
   *
   * @param  mixed  $other  The object to be compared for equality with this collection
   *
   * @return  bool  <tt>true</tt> if the specified object is equal to this collection
   */
  function equals($other): bool;

  /**
   * Returns a sequential <tt>Stream</tt> with this collection as its source.
   *
   * @return  Stream  A sequential <tt>Stream</tt> over the elements in this collection
   */
  function stream(): Stream;
}
