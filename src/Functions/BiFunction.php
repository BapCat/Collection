<?php declare(strict_types=1); namespace BapCat\Collection\Functions;

/**
 * Represents a function that accepts two arguments and produces a result.
 * This is the two-arity specialization of {@link Function}.
 *
 * <p>This is a <a href="package-summary.html">functional interface</a>
 * whose functional method is {@link #apply(Object, Object)}.
 *
 * @param <T> the type of the first argument to the function
 * @param <U> the type of the second argument to the function
 * @param <R> the type of the result of the function
 *
 * @see Function
 * @since 1.8
 */
interface BiFunction {
  /**
   * Applies this function to the given arguments.
   *
   * @param  mixed  $t  The first function argument
   * @param  mixed  $u  The second function argument
   *
   * @return  mixed  The function result
   */
  function apply($t, $u);
}
