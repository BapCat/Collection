<?php declare(strict_types=1); namespace BapCat\Collection\Functions;

/**
 * Represents an operation that accepts two input arguments and returns no
 * result.  This is the two-arity specialization of {@link Consumer}.
 * Unlike most other functional interfaces, {@code BiConsumer} is expected
 * to operate via side-effects.
 *
 * <p>This is a <a href="package-summary.html">functional interface</a>
 * whose functional method is {@link #accept(Object, Object)}.
 *
 * @param <T> the type of the first argument to the operation
 * @param <U> the type of the second argument to the operation
 *
 * @see Consumer
 * @since 1.8
 */
interface BiConsumer {
  /**
   * Performs this operation on the given arguments.
   *
   * @param  mixed  $t  The first input argument
   * @param  mixed  $u  The second input argument
   *
   * @return  void
   */
  function accept($t, $u): void;
}
