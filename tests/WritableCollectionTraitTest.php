<?php

use BapCat\Collection\Exceptions\NoSuchKeyException;
use BapCat\Collection\Traits\WritableCollectionTrait;

class WritableCollectionTraitTest extends PHPUnit_Framework_TestCase {
  private $trait;
  private $collection;
  private $lazy;
  
  public function setUp() {
    $this->trait = $this
      ->getMockBuilder(WritableCollectionTrait::class)
      ->getMockForTrait()
    ;
    
    $class = new ReflectionClass($this->trait);
    $this->collection = $class->getProperty('collection');
    $this->collection->setAccessible(true);
    
    $this->lazy = $class->getProperty('lazy');
    $this->lazy->setAccessible(true);
  }
  
  public function testAdd() {
    $this->trait->add('test');
    
    $this->assertSame(['test'], $this->getCollection());
    
    $this->trait->add('test2');
    
    $this->assertSame(['test', 'test2'], $this->getCollection());
  }
  
  public function testSet() {
    $this->trait->set('key1', 'val1');
    
    $this->assertSame(['key1' => 'val1'], $this->getCollection());
    
    $this->trait->set('key2', 'val2');
    
    $this->assertSame(['key1' => 'val1', 'key2' => 'val2'], $this->getCollection());
  }
  
  public function testAddAndSet() {
    $this->trait->add('test');
    $this->trait->set('key1', 'val1');
    
    $this->assertSame(['test', 'key1' => 'val1'], $this->getCollection());
  }
  
  public function testLazyLoading() {
    $val1 = function($key) {
      return 'val1';
    };
    
    $val2 = function($key) {
      return 'val2';
    };
    
    $this->trait->lazy('key1', $val1);
    $this->trait->lazy('key2', $val2);
    
    $this->assertSame([
      'key1' => $val1,
      'key2' => $val2
    ], $this->getCollection());
    
    $this->assertSame([
      'key1' => 'key1',
      'key2' => 'key2'
    ], $this->getLazy());
  }
  
  public function testTake() {
    $this->setCollection(['key1' => 'val1', 'key2' => 'val2']);
    
    $this->assertSame(['key1' => 'val1', 'key2' => 'val2'], $this->getCollection());
    $this->assertSame('val1', $this->trait->take('key1'));
    $this->assertSame(['key2' => 'val2'], $this->getCollection());
    $this->assertSame('already removed', $this->trait->take('key1', 'already removed'));
    
    $this->setExpectedException(NoSuchKeyException::class);
    $this->trait->take('non-existant key');
  }
  
  public function testLazyTake() {
    $val1 = function($key) {
      return 'val1';
    };
    
    $val2 = function($key) {
      return 'val2';
    };
    
    $this->trait->lazy('key1', $val1);
    $this->trait->lazy('key2', $val2);
    
    $this->assertSame('val1', $this->trait->take('key1'));
    
    $this->assertSame(['key2' => $val2], $this->getCollection());
    $this->assertSame(['key2' => 'key2'], $this->getLazy());
  }
  
  public function testRemove() {
    $this->setCollection(['key1' => 'val1', 'key2' => 'val2']);
    
    $this->assertSame(['key1' => 'val1', 'key2' => 'val2'], $this->getCollection());
    
    $this->trait->remove('key1');
    
    $this->assertSame(['key2' => 'val2'], $this->getCollection());
  }
  
  public function testClear() {
    $this->setCollection(['key1' => 'val1', 'key2' => 'val2']);
    
    $this->trait->clear();
    
    $this->assertEmpty($this->getCollection());
  }
  
  private function getCollection() {
    return $this->collection->getValue($this->trait);
  }
  
  private function setCollection(array $collection) {
    $this->collection->setValue($this->trait, $collection);
  }
  
  // Go on, you know you want to
  private function getLazy() {
    return $this->lazy->getValue($this->trait);
  }
}
