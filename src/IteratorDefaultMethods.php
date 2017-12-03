<?php declare(strict_types=1); namespace BapCat\Collection;

use BapCat\Collection\Functions\Consumer;

/**
 * Contains default implementations for several {@link Iterator} methods
 */
trait IteratorDefaultMethods {
  /**
   * See {@link Iterator::remove}
   *
   * @return  void
   *
   * @throws  UnsupportedOperationException
   */
  public function remove() : void {
    throw new UnsupportedOperationException("remove");
  }

  /**
   * See {@link Iterator::forEachRemaining}
   *
   * @param  Consumer  $action
   *
   * @return  void
   */
  public function forEachRemaining(Consumer $action) : void {
    while($this->hasNext()) {
      $action->accept($this->next());
    }
  }

  /**
   * See {@link Iterator::hasNext}
   *
   * @return  bool
   */
  public abstract function hasNext(): bool;

  /**
   * See {@link Iterator::next}
   *
   * @return  mixed
   */
  public abstract function next();
}
