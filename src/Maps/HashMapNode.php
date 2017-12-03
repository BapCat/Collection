<?php declare(strict_types=1); namespace BapCat\Collection\Maps;

/**
* Basic hash bin node, used for most entries.  (See below for
* TreeNode subclass, and in LinkedHashMap for its Entry subclass.)
*/
class HashMapNode implements Entry {
  private $hash;
  private $key;
  private $value;
  private $next;

  public function __construct(int $hash, $key, $value, ?HashMapNode $next) {
    $this->hash  = $hash;
    $this->key   = $key;
    $this->value = $value;
    $this->next  = $next;
  }

  public function getHash(): int     { return $this->hash; }
  public function getKey()           { return $this->key; }
  public function getValue()         { return $this->value; }
  public function getNext(): ?HashMapNode { return $this->next; }
  public function toString(): string { return "{$this->key}={$this->value}"; }

  public function hashCode(): int {
    return crc32(serialize($this));
  }

  public function setValue($newValue) {
    $oldValue = $this->value;
    $this->value = $newValue;
    return $oldValue;
  }

  public function equals($o): bool {
    if($o === $this) {
      return true;
    }

    if(!($o instanceof Entry)) {
      return false;
    }

    return $this->hashCode() === $o->hashCode();
  }
}
