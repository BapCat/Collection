<?php declare(strict_types=1); namespace BapCat\Collection\Lists;

use BapCat\Collection\IllegalArgumentException;
use BapCat\Collection\IllegalStateException;
use BapCat\Collection\Iterator;
use BapCat\Collection\NoSuchElementException;
use BapCat\Collection\UnsupportedOperationException;

/**
 * An iterator for lists that allows the programmer
 * to traverse the list in either direction, modify
 * the list during iteration, and obtain the iterator's
 * current position in the list. A <tt>ListIterator</tt>
 * has no current element; its <I>cursor position</I> always
 * lies between the element that would be returned by a call
 * to <tt>previous()</tt> and the element that would be
 * returned by a call to <tt>next()</tt>.
 * An iterator for a list of length <tt>n</tt> has <tt>n+1</tt> possible
 * cursor positions, as illustrated by the carets (<$tt>^</tt>) below:
 * <PRE>
 *                      Element(0)   Element(1)   Element(2)   ... Element(n-1)
 * cursor positions:  ^            ^            ^            ^                  ^
 * </PRE>
 * Note that the {@link #remove} and {@link #set(Object)} methods are
 * <i>not</i> defined in terms of the cursor position;  they are defined to
 * operate on the last element returned by a call to {@link #next} or
 * {@link #previous()}.
 *
 * <p>This interface is a member of the
 * <a href="{@docRoot}/../technotes/guides/collections/index.html">
 * Java Collections Framework</a>.
 */
interface ListIterator extends Iterator {
  // Query Operations

  /**
   * Returns <tt>true</tt> if this list iterator has more elements when
   * traversing the list in the forward direction. (In other words,
   * returns <tt>true</tt> if {@link #next} would return an element rather
   * than throwing an exception.)
   *
   * @return  bool  <tt>true</tt> if the list iterator has more elements when
   *                traversing the list in the forward direction
   */
  function hasNext(): bool;

  /**
   * Returns the next element in the list and advances the cursor position.
   * This method may be called repeatedly to iterate through the list,
   * or intermixed with calls to {@link #previous} to go back and forth.
   * (Note that alternating calls to <tt>next</tt> and <tt>previous</tt>
   * will return the same element repeatedly.)
   *
   * @return  mixed  The next element in the list
   *
   * @throws  NoSuchElementException if the iteration has no next element
   */
  function next();

  /**
   * Returns <tt>true</tt> if this list iterator has more elements when
   * traversing the list in the reverse direction.  (In other words,
   * returns <tt>true</tt> if {@link #previous} would return an element
   * rather than throwing an exception.)
   *
   * @return  bool  <tt>true</tt> if the list iterator has more elements when
   *                traversing the list in the reverse direction
   */
  function hasPrevious(): bool;

  /**
   * Returns the previous element in the list and moves the cursor
   * position backwards.  This method may be called repeatedly to
   * iterate through the list backwards, or intermixed with calls to
   * {@link #next} to go back and forth.  (Note that alternating calls
   * to <tt>next</tt> and <tt>previous</tt> will return the same
   * element repeatedly.)
   *
   * @return  mixed  The previous element in the list
   *
   * @throws NoSuchElementException if the iteration has no previous element
   */
  function previous();

  /**
   * Returns the index of the element that would be returned by a
   * subsequent call to {@link #next}. (Returns list size if the list
   * iterator is at the end of the list.)
   *
   * @return  int  The index of the element that would be returned by a
   *               subsequent call to <tt>next</tt>, or list size if the list
   *               iterator is at the end of the list
   */
  function nextIndex(): int;

  /**
   * Returns the index of the element that would be returned by a
   * subsequent call to {@link #previous}. (Returns -1 if the list
   * iterator is at the beginning of the list.)
   *
   * @return  int  The index of the element that would be returned by a
   *               subsequent call to <tt>previous</tt>, or -1 if the list
   *               iterator is at the beginning of the list
   */
  function previousIndex(): int;

  // Modification Operations

  /**
   * Removes from the list the last element that was returned by {@link
   * #next} or {@link #previous} (optional operation).  This call can
   * only be made once per call to <tt>next</tt> or <tt>previous</tt>.
   * It can be made only if {@link #add} has not been
   * called after the last call to <tt>next</tt> or <tt>previous</tt>.
   *
   * @throws UnsupportedOperationException if the <tt>remove</tt>
   *         operation is not supported by this list iterator
   * @throws IllegalStateException if neither <tt>next</tt> nor
   *         <tt>previous</tt> have been called, or <tt>remove</tt> or
   *         <tt>add</tt> have been called after the last call to
   *         <tt>next</tt> or <tt>previous</tt>
   */
  function remove(): void;

  /**
   * Replaces the last element returned by {@link #next} or
   * {@link #previous} with the specified element (optional operation).
   * This call can be made only if neither {@link #remove} nor {@link
   * #add} have been called after the last call to <tt>next</tt> or
   * <tt>previous</tt>.
   *
   * @param  mixed  $value  The element with which to replace the last element returned by
   *                        <tt>next</tt> or <tt>previous</tt>
   * @throws UnsupportedOperationException if the <tt>set</tt> operation
   *         is not supported by this list iterator
   * @throws IllegalArgumentException if some aspect of the specified
   *         element prevents it from being added to this list
   * @throws IllegalStateException if neither <tt>next</tt> nor
   *         <tt>previous</tt> have been called, or <tt>remove</tt> or
   *         <tt>add</tt> have been called after the last call to
   *         <tt>next</tt> or <tt>previous</tt>
   */
  function set($value): void;

  /**
   * Inserts the specified element into the list (optional operation).
   * The element is inserted immediately before the element that
   * would be returned by {@link #next}, if any, and after the element
   * that would be returned by {@link #previous}, if any.  (If the
   * list contains no elements, the new element becomes the sole element
   * on the list.)  The new element is inserted before the implicit
   * cursor: a subsequent call to <tt>next</tt> would be unaffected, and a
   * subsequent call to <tt>previous</tt> would return the new element.
   * (This call increases by one the value that would be returned by a
   * call to <tt>nextIndex</tt> or <tt>previousIndex</tt>.)
   *
   * @param  mixed  $value  The element to insert
   *
   * @throws UnsupportedOperationException if the <tt>add</tt> method is
   *         not supported by this list iterator
   * @throws IllegalArgumentException if some aspect of this element
   *         prevents it from being added to this list
   */
  function add($value): void;
}
