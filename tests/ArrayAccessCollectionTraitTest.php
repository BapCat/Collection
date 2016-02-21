<?php

use BapCat\Collection\Traits\ArrayAccessCollectionTrait;

class ArrayAccessCollectionTraitTest extends PHPUnit_Framework_TestCase {
  private $trait;
  private $collection;
  
  public function setUp() {
    $this->trait = $this->mockTrait();
  }
  
  public function testAccess() {
    $this->assertFalse($this->trait->offsetExists('key'));
    
    $this->trait->offsetSet(null, 'val');
    $this->assertSame('val', $this->trait->offsetGet(0));
    
    $this->trait->offsetSet('key1', 'val1');
    $this->assertTrue($this->trait->offsetExists('key1'));
    $this->assertSame('val1', $this->trait->offsetGet('key1'));
    
    $this->trait->offsetUnset('key1');
    $this->assertFalse($this->trait->offsetExists('key1'));
  }
  
  private function mockTrait() {
    $trait = $this
      ->getMockBuilder(ArrayAccessCollectionTrait::class)
      ->setMethods(['has', 'get', 'add', 'set', 'remove'])
      ->getMockForTrait()
    ;
    
    $trait
      ->method('has')
      ->will($this->returnCallback(function($key) {
        return isset($this->collection[$key]);
      }))
    ;
    
    $trait
      ->method('get')
      ->will($this->returnCallback(function($key) {
        return $this->collection[$key];
      }))
    ;
    
    $trait
      ->method('add')
      ->will($this->returnCallback(function($val) {
        $this->collection[] = $val;
      }))
    ;
    
    $trait
      ->method('set')
      ->will($this->returnCallback(function($key, $val) {
        $this->collection[$key] = $val;
      }))
    ;
    
    $trait
      ->method('remove')
      ->will($this->returnCallback(function($key) {
        unset($this->collection[$key]);
      }))
    ;
    
    return $trait;
  }
}
