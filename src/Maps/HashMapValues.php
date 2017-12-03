<?php declare(strict_types=1);

namespace BapCat\Collection\Maps;


use BapCat\Collection\AbstractCollection;
use BapCat\Collection\Functions\Consumer;
use BapCat\Collection\Iterator;
use BapCat\Collection\NullPointerException;

class HashMapValues extends AbstractCollection {
  private $hashMap;
  private $table;

  public function __construct(HashMap $hashMap, array &$table) {
    $this->hashMap = $hashMap;
    $this->table = &$table;
  }

  public function size(): int {
    return $this->hashMap->size();
  }

  public function clear(): void {
    $this->hashMap->clear();
  }

  public function iterator(): Iterator {
    return new ValueIterator();
  }

  public function contains($o): bool {
    return $this->hashMap->containsValue($o);
  }

  public function each(Consumer $action): void {
    if($action === null) {
      throw new NullPointerException();
    }

    if($this->hashMap->size() > 0 && ($tab = &$this->table) !== null) {
      for($i = 0; $i < count($tab); ++$i) {
        for($e = $tab[$i]; $e !== null; $e = $e->getNext()) {
          $action->accept($e->getValue());
        }
      }
    }
  }
}
