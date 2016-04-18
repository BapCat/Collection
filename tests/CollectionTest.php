<?php

use BapCat\Collection\Collection;
use BapCat\Collection\Interfaces\Collection as CollectionInterface;
use BapCat\Collection\Interfaces\WritableCollection;

class CollectionTest extends PHPUnit_Framework_TestCase {
  public function testCollectionImplementsProperInterfaces() {
    $collection = new Collection();
    
    $this->assertInstanceOf(CollectionInterface::class, $collection);
    $this->assertInstanceOf(WritableCollection::class, $collection);
    $this->assertInstanceOf(IteratorAggregate::class, $collection);
    
    $this->assertNotInstanceOf(ArrayAccess::class, $collection);
  }
  
  public function testConstructEmpty() {
    $collection = new Collection();
    
    $this->assertEmpty($collection->all());
  }
  
  public function testConstructWithContent() {
    $values = ['key1' => 'val1', 'key2' => 'val2'];
    
    $collection = new Collection($values);
    
    $this->assertSame($values, $collection->all());
  }
  
  public function testInstantiator() {
    $collection = new Collection();
    $this->assertInstanceOf(Collection::class, $collection->__new([]));
  }
  
  public function testCountable() {
    $collection = new Collection();
    $this->assertSame(0, count($collection));
    $collection = new Collection([1,2,3]);
    $this->assertSame(3, count($collection));
  }
}
