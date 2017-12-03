<?php declare(strict_types=1); namespace BapCat\Collection\Functions;

/**
 * Represents a function that accepts one argument and produces a result.
 *
 * <p>This is a <a href="package-summary.html">functional interface</a>
 * whose functional method is {@link #apply(Object)}.
 *
 * @param <T> the type of the input to the function
 * @param <R> the type of the result of the function
 *
 * @since 1.8
 */
interface Func {
  /**
   * Applies this function to the given argument.
   *
   * @param  mixed  $t  The function argument
   *
   * @return  mixed  The function result
   */
  function apply($t);
}
