<?php declare(strict_types=1); namespace BapCat\Collection\Functions;

/**
 * Represents an operation that accepts a single input argument and returns no
 * result. Unlike most other functional interfaces, {@code Consumer} is expected
 * to operate via side-effects.
 *
 * <p>This is a <a href="package-summary.html">functional interface</a>
 * whose functional method is {@link #accept(Object)}.
 */
interface Consumer {
  /**
   * Performs this operation on the given argument.
   *
   * @param  mixed  $t  The input argument
   */
  function accept($t): void;
}