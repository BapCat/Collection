<?php namespace LordMonoxide\Collection\Traits;

trait ArrayAccessCollectionTrait {
  public function offsetSet($offset, $value) {
    if(is_null($offset)) {
      $this->add($value);
    } else {
      $this->set($offset, $value);
    }
  }
  
  public function offsetExists($offset) {
    return $this->has($offset);
  }
  
  public function offsetUnset($offset) {
    $this->remove($offset);
  }
  
  public function offsetGet($offset) {
    return $this->get($offset);
  }
}
