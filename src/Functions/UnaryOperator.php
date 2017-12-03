<?php declare(strict_types=1); namespace BapCat\Collection\Functions;

/**
 * Represents an operation on a single operand that produces a result of the
 * same type as its operand.  This is a specialization of {@code Function} for
 * the case where the operand and result are of the same type.
 *
 * <p>This is a <a href="package-summary.html">functional interface</a>
 * whose functional method is {@link #apply(Object)}.
 *
 * @param <T> the type of the operand and result of the operator
 *
 * @see Function
 * @since 1.8
 */
interface UnaryOperator extends Func {
  /**
   * Applies this function to the given argument.
   *
   * @param  mixed  $t  The function argument
   *
   * @return  mixed  The function result
   */
  function apply($t);
}
