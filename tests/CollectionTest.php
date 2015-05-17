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
  
  public function testGetDefault() {
    $collection = new Collection();
    $this->assertEquals('OrDoI', $collection->get('IDontExist', 'OrDoI'));
  }
  
  public function testGetInvalidKey() {
    $this->setExpectedException('LordMonoxide\Collection\NoSuchKeyException');
    $collection = new Collection();
    $collection->get('IDontExist');
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
  
  public function testFirst() {
    $collection = new Collection();
    
    $collection->add('Test');
    
    $this->assertEquals('Test', $collection->first());
  }
  
  public function testFirstEmpty() {
    $this->setExpectedException('LordMonoxide\Collection\NoSuchKeyException');
    $collection = new Collection();
    $collection->first();
  }
  
  public function testAll() {
    $collection = new Collection();
    $this->assertEquals([], $collection->all());
    $collection->set('key1', 'val1');
    $this->assertEquals(['key1' => 'val1'], $collection->all());
    $collection->set('key2', 'val2');
    $this->assertEquals(['key1' => 'val1', 'key2' => 'val2'], $collection->all());
  }
  
  public function testKeys() {
    $collection = new Collection();
    $this->assertEquals([], $collection->keys());
    $collection->set('key1', 'val1');
    $this->assertEquals(['key1'], $collection->keys());
    $collection->set('key2', 'val2');
    $this->assertEquals(['key1', 'key2'], $collection->keys());
  }
  
  public function testValues() {
    $collection = new Collection();
    $this->assertEquals([], $collection->values());
    $collection->set('key1', 'val1');
    $this->assertEquals(['val1'], $collection->values());
    $collection->set('key2', 'val2');
    $this->assertEquals(['val1', 'val2'], $collection->values());
  }
  
  public function testFor() {
    $collection = new Collection();
    $collection->set('key1', 'val1');
    $collection->set('key2', 'val2');
    $collection->set('key3', 'val3');
    
    $kv = [];
    
    foreach($collection as $key => $val) {
      $kv[$key] = $val;
    }
    
    $this->assertEquals([
      'key1' => 'val1',
      'key2' => 'val2',
      'key3' => 'val3'
    ], $kv);
  }
}
