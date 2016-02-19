<?php

use BapCat\Collection\Exceptions\NoSuchKeyException;
use BapCat\Collection\Traits\WritableCollectionTrait;

class WritableCollectionTraitTest extends PHPUnit_Framework_TestCase {
  private $trait;
  private $collection;
  
  public function setUp() {
    $this->trait = $this
      ->getMockBuilder(WritableCollectionTrait::class)
      ->getMockForTrait()
    ;
    
    $class = new ReflectionClass($this->trait);
    $this->collection = $class->getProperty('collection');
    $this->collection->setAccessible(true);
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
  
  public function testTake() {
    $this->setCollection(['key1' => 'val1', 'key2' => 'val2']);
    
    $this->assertSame(['key1' => 'val1', 'key2' => 'val2'], $this->getCollection());
    $this->assertSame('val1', $this->trait->take('key1'));
    $this->assertSame(['key2' => 'val2'], $this->getCollection());
    $this->assertSame('already removed', $this->trait->take('key1', 'already removed'));
    
    $this->setExpectedException(NoSuchKeyException::class);
    $this->trait->take('non-existant key');
  }
  
  public function testRemove() {
    $this->setCollection(['key1' => 'val1', 'key2' => 'val2']);
    
    $this->assertSame(['key1' => 'val1', 'key2' => 'val2'], $this->getCollection());
    
    $this->trait->remove('key1');
    
    $this->assertSame(['key2' => 'val2'], $this->getCollection());
  }
  
  private function getCollection() {
    return $this->collection->getValue($this->trait);
  }
  
  private function setCollection(array $collection) {
    $this->collection->setValue($this->trait, $collection);
  }
}
