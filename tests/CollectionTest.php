<?php

use BapCat\Collection\Collection;
use BapCat\Collection\Interfaces\Collection as CollectionInterface;
use BapCat\Collection\Interfaces\WritableCollection;
use PHPUnit\Framework\TestCase;

class CollectionTest extends TestCase {
  public function testCollectionImplementsProperInterfaces() {
    $collection = new Collection();
    
    $this->assertInstanceOf(CollectionInterface::class, $collection);
    $this->assertInstanceOf(WritableCollection::class, $collection);
    $this->assertInstanceOf(IteratorAggregate::class, $collection);
    
    $this->assertNotInstanceOf(ArrayAccess::class, $collection);
  }
  
  public function testConstructEmpty() {
    $collection = new Collection();
    
    $this->assertEmpty($collection->toArray());
  }
  
  public function testConstructWithContent() {
    $values = ['key1' => 'val1', 'key2' => 'val2'];
    
    $collection = new Collection($values);
    
    $this->assertSame($values, $collection->toArray());
  }
  
  public function testInstantiator() {
    $collection = new Collection();
    
    $this->assertInstanceOf(Collection::class, $collection->__new([]));
  }
}
