<?php declare(strict_types=1); namespace BapCat\Collection\Maps;

use BapCat\Collection\Functions\Consumer;
use BapCat\Collection\Iterator;
use BapCat\Collection\NullPointerException;
use BapCat\Collection\Sets\AbstractSet;

class HashMapKeySet extends AbstractSet {
  private $hashMap;
  private $table;

  public function __construct(HashMap $hashMap, array &$table) {
    $this->hashMap = $hashMap;
    $this->table = &$table;
  }

  public function size(): int {
    return $this->size();
  }

  public function clear(): void {
    $this->hashMap->clear();
  }

  public function iterator(): Iterator {
    return new KeyIterator();
  }

  public function contains($o): bool {
    return $this->hashMap->containsKey($o);
  }

  public function remove($key): bool {
    return removeNode(crc32(serialize($key)), $key, null, false, true) !== null;
  }

  public function each(Consumer $action) : void {
    if($action === null) {
      throw new NullPointerException();
    }

    if($this->hashMap->size() > 0 && ($tab = &$this->table) !== null) {
      for($i = 0; $i < count($tab); ++$i) {
        for($e = $tab[$i]; $e !== null; $e = $e->getNext()) {
          $action->accept($e->getKey());
        }
      }
    }
  }
}
