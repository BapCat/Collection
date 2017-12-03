<?php declare(strict_types=1); namespace BapCat\Collection\Functions;

/**
 * Represents a predicate (boolean-valued function) of one argument.
 *
 * <p>This is a <a href="package-summary.html">functional interface</a>
 * whose functional method is {@link #test(Object)}.
 */
interface Predicate {
  /**
   * Evaluates this predicate on the given argument.
   *
   * @param  mixed  $t  The input argument
   *
   * @return  bool  <tt>true</tt> if the input argument matches the predicate, otherwise <tt>false</tt>
   */
  function test($t): bool;
}
