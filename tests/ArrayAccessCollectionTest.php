<?php

use BapCat\Collection\ArrayAccessCollection;
use BapCat\Collection\Interfaces\Collection;
use BapCat\Collection\Interfaces\WritableCollection;
use PHPUnit\Framework\TestCase;

class ArrayAccessCollectionTest extends TestCase {
  public function testCollectionImplementsProperInterfaces() {
    $collection = new ArrayAccessCollection();
    
    $this->assertInstanceOf(Collection::class, $collection);
    $this->assertInstanceOf(WritableCollection::class, $collection);
    $this->assertInstanceOf(IteratorAggregate::class, $collection);
    $this->assertInstanceOf(ArrayAccess::class, $collection);
  }
  
  public function testConstructEmpty() {
    $collection = new ArrayAccessCollection();
    
    $this->assertEmpty($collection->toArray());
  }
  
  public function testConstructWithContent() {
    $values = ['key1' => 'val1', 'key2' => 'val2'];
    
    $collection = new ArrayAccessCollection($values);
    
    $this->assertSame($values, $collection->toArray());
  }
  
  public function testInstantiator() {
    $collection = new ArrayAccessCollection();
    
    $this->assertInstanceOf(ArrayAccessCollection::class, $collection->__new([]));
  }
}
