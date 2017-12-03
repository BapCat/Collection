<?php declare(strict_types=1); namespace BapCat\Collection;

use BapCat\Collection\Functions\Predicate;

/**
 * Contains default implementations for several {@link Collection} methods
 */
trait CollectionDefaultMethods {
  /**
   * See {@link Collection::removeIf()}
   *
   * @param  Predicate $filter
   *
   * @return  bool
   *
   * @throws  IllegalStateException
   * @throws  NoSuchElementException
   * @throws  UnsupportedOperationException
   */
  public function removeIf(Predicate $filter): bool {
    $removed = false;
    $each = $this->iterator();

    while($each->hasNext()) {
      if($filter->test($each->next())) {
        $each->remove();
        $removed = true;
      }
    }

    return $removed;
  }

  /**
   * See {@link Collection::iterator()}
   *
   * @return  Iterator
   */
  public function getIterator(): Iterator {
    return $this->iterator();
  }

  /**
   * See {@link Collection::stream()}
   *
   * @return  Stream
   */
  public function stream(): Stream {
    return StreamSupport::stream($this->iterator(), false);
  }

  /**
   * See {@link Collection::iterator()}
   *
   * @return  Iterator
   */
  public abstract function iterator(): Iterator;
}
