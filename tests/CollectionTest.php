<?php

use LordMonoxide\Collection\Collection;

class CollectionTest extends PHPUnit_Framework_TestCase {
  public function testHas() {
    $collection = new Collection();
    $collection->set('key', 'val');
    $collection->lazy('lazy', function($key) { return 'val'; });
    
    $this->assertTrue($collection->has('key'));
    $this->assertTrue($collection->has('lazy'));
  }
  
  public function testCount() {
    $collection = new Collection();
    $this->assertEquals(0, $collection->size());
    $collection->set('key', 'val');
    $this->assertEquals(1, $collection->size());
    $collection->lazy('lazy', function($key) { return 'val'; });
    $this->assertEquals(2, $collection->size());
  }
  
  public function testBasic() {
    $collection = new Collection();
    
    $collection->set('key1', 'val1');
    $collection->set('key2', 'val2');
    
    $this->assertEquals('val1', $collection->get('key1'));
    $this->assertEquals('val2', $collection->get('key2'));
  }
  
  public function testLazy() {
    $collection = new Collection();
    
    $collection->lazy('key', function($key) { return 'val'; });
    
    $this->assertEquals('val', $collection->get('key'));
  }
  
  public function testAdd() {
    $collection = new Collection();
    
    $collection->add('Test');
    
    $this->assertEquals('Test', $collection->get(0));
  }
}
