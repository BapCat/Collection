<?php declare(strict_types=1); namespace BapCat\Collection\Maps;

use BapCat\Collection\Functions\Consumer;
use BapCat\Collection\Iterator;
use BapCat\Collection\NullPointerException;
use BapCat\Collection\Sets\AbstractSet;

class HashMapEntrySet extends AbstractSet {
  private $hashMap;
  private $table;

  public function __construct(HashMap $hashMap, array &$table) {
    $this->hashMap = $hashMap;
    $this->table = &$table;
  }

  public function size(): int                 { return $this->hashMap->size(); }
  public function clear(): void               { $this->hashMap->clear(); }
  public function iterator(): Iterator {
    return new EntryIterator();
  }

  public function contains($o): bool {
    if(!($o instanceof Entry)) {
      return false;
    }

    /** @var  Entry  $o */
    $key = $o->getKey();
    $candidate = getNode(hash($key), $key);
    return $candidate !== null && $candidate === $o;
  }

  public function remove($o): bool {
    if($o instanceof Entry) {
      /** @var  Entry  $o */
      $key = $o->getKey();
      $value = $o->getValue();
      return removeNode(hash($key), $key, $value, true, true) !== null;
    }

    return false;
  }

  public function each(Consumer $action): void {
    if($action === null) {
      throw new NullPointerException();
    }

    if($this->hashMap->size() > 0 && ($tab = &$this->table) !== null) {
      for($i = 0; $i < count($tab); ++$i) {
        for($e = $tab[$i]; $e !== null; $e = $e->getNext()) {
          $action->accept($e);
        }
      }
    }
  }
}
