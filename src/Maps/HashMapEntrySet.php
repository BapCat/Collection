<?php declare(strict_types=1); namespace BapCat\Collection\Maps;

use BapCat\Collection\Functions\Consumer;
use BapCat\Collection\Iterator;
use BapCat\Collection\NullPointerException;
use BapCat\Collection\Sets\AbstractSet;

class HashMapEntrySet extends AbstractSet {
  private $hashMap;
  private $getNode;
  private $removeNode;
  private $table;

  public function __construct(HashMap $hashMap, callable $getNode, callable $removeNode, array &$table) {
    $this->hashMap = $hashMap;
    $this->getNode = $getNode;
    $this->removeNode = $removeNode;
    $this->table = &$table;
  }

  public function size(): int {
    return $this->hashMap->size();
  }

  public function clear(): void {
    $this->hashMap->clear();
  }

  public function iterator(): Iterator {
    return new HashMapEntryIterator();
  }

  public function contains($o): bool {
    if(!($o instanceof Entry)) {
      return false;
    }

    /** @var  Entry  $o */
    $key = $o->getKey();
    $candidate = ($this->getNode)($key);
    return $candidate !== null && $candidate === $o;
  }

  public function remove($o): bool {
    if($o instanceof Entry) {
      /** @var  Entry  $o */
      $key = $o->getKey();
      $value = $o->getValue();
      return ($this->removeNode)($key, $value, true, true) !== null;
    }

    return false;
  }

  public function each(Consumer $action): void {
    for($i = 0; $i < $this->hashMap->size(); ++$i) {
      for($e = $this->table[$i]; $e !== null; $e = $e->getNext()) {
        $action->accept($e);
      }
    }
  }
}
