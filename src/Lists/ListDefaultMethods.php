<?php declare(strict_types=1); namespace BapCat\Collection\Lists;

use BapCat\Collection\Comparator;
use BapCat\Collection\Functions\UnaryOperator;
use BapCat\Collection\IllegalArgumentException;
use BapCat\Collection\IllegalStateException;
use BapCat\Collection\NoSuchElementException;
use BapCat\Collection\UnsupportedOperationException;

/**
 * Contains default implementations for several {@link ListCollection} methods
 */
trait ListDefaultMethods {
  /**
   * See {@link ListCollection::replaceAll()}
   *
   * @param  UnaryOperator  $operator
   *
   * @return  void
   *
   * @throws IllegalArgumentException
   * @throws IllegalStateException
   * @throws NoSuchElementException
   * @throws UnsupportedOperationException
   */
  public function replaceAll(UnaryOperator $operator): void  {
    $li = $this->listIterator();

    while($li->hasNext()) {
      $li->set($operator->apply($li->next()));
    }
  }

  /**
   * See {@link ListCollection::sort()}
   *
   * @param  Comparator|null  $c
   *
   * @throws IllegalArgumentException
   * @throws IllegalStateException
   * @throws NoSuchElementException
   * @throws UnsupportedOperationException
   */
  public function sort(?Comparator $c): void {
    $a = $this->toArray();
    usort($a, [$c, 'compare']);
    $i = $this->listIterator();

    foreach($a as $e) {
      $i->next();
      $i->set($e);
    }
  }

  /**
   * See {@link ListCollection::listIterator()}
   *
   * @param  int  $index
   *
   * @return  ListIterator
   */
  public abstract function listIterator(int $index = 0): ListIterator;

  /**
   * See {@link ListCollection::toArray()}
   *
   * @return  array
   */
  public abstract function toArray(): array;
}
