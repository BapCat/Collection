<?php declare(strict_types=1); namespace BapCat\Collection\Maps;

use BapCat\Collection\AbstractCollection;
use BapCat\Collection\Iterator;

/**
 * A {@link Map}'s {@link Collection} of values
 */
class ValueCollection extends AbstractCollection {
  /**
   * @var  Map
   */
  private $map;

  /**
   * @param  Map  $map
   */
  public function __construct(Map $map) {
    $this->map = $map;
  }

  /**
   * @return  Iterator
   */
  public function iterator(): Iterator {
    return new ValueCollectionIterator($this->map->entrySet());
  }

  /**
   * @return  int
   */
  public function size(): int {
    return $this->map->size();
  }

  /**
   * @return  bool
   */
  public function isEmpty(): bool {
    return $this->map->isEmpty();
  }

  /**
   * @var  void
   */
  public function clear(): void {
    $this->map->clear();
  }

  /**
   * @param  mixed  $v
   *
   * @return  bool
   */
  public function contains($v): bool {
    return $this->map->containsValue($v);
  }
}
