<?php declare(strict_types=1); namespace BapCat\Collection;

use BapCat\Collection\Functions\Consumer;

/**
 * An iterator over a collection.  <tt>Iterator</tt> takes the place of
 * {@link Enumeration} in the Java Collections Framework.  Iterators
 * differ from enumerations in two ways:
 *
 * <ul>
 *      <li> Iterators allow the caller to remove elements from the
 *           underlying collection during the iteration with well-defined
 *           semantics.
 *      <li> Method names have been improved.
 * </ul>
 *
 * <p>This interface is a member of the
 * <a href="{@docRoot}/../technotes/guides/collections/index.html">
 * Java Collections Framework</a>.
 */
interface Iterator extends \Iterator {
  /**
   * Returns <tt>true</tt> if the iteration has more elements.
   * (In other words, returns <tt>true</tt> if {@link #next} would
   * return an element rather than throwing an exception.)
   *
   * @return  bool  <tt>true</tt> if the iteration has more elements
   */
  function hasNext(): bool;

  /**
   * Returns the next element in the iteration.
   *
   * @return  mixed  The next element in the iteration
   *
   * @throws NoSuchElementException if the iteration has no more elements
   */
  function next();

  /**
   * Removes from the underlying collection the last element returned
   * by this iterator (optional operation).  This method can be called
   * only once per call to {@link #next}.  The behavior of an iterator
   * is unspecified if the underlying collection is modified while the
   * iteration is in progress in any way other than by calling this
   * method.
   *
   * @return  void
   *
   * @throws UnsupportedOperationException if the <tt>remove</tt>
   *         operation is not supported by this iterator
   *
   * @throws IllegalStateException if the <tt>next</tt> method has not
   *         yet been called, or the <tt>remove</tt> method has already
   *         been called after the last call to the <tt>next</tt>
   *         method
   */
  function remove(): void;

  /**
   * Performs the given action for each remaining element until all elements
   * have been processed or the action throws an exception.  Actions are
   * performed in the order of iteration, if that order is specified.
   * Exceptions thrown by the action are relayed to the caller.
   *
   * @param  Consumer  $action  The action to be performed for each element
   *
   * @return  void
   */
  function forEachRemaining(Consumer $action): void;
}
