<?php declare(strict_types=1); namespace BapCat\Collection\Maps;

use BapCat\Collection\Iterator;
use BapCat\Collection\Sets\AbstractSet;

/**
 * A {@link Map}'s {@link Set} of keys
 */
class AbstractMapKeySet extends AbstractSet {
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
    return new AbstractMapKeySetIterator($this->map->entrySet());
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
   * @return  void
   */
  public function clear(): void {
    $this->map->clear();
  }

  /**
   * @param  mixed  $k
   *
   * @return  bool
   */
  public function contains($k): bool {
    return $this->map->containsKey($k);
  }
}
