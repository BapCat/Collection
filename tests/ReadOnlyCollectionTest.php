<?php

use BapCat\Collection\ReadOnlyCollection;
use BapCat\Collection\Interfaces\Collection;
use BapCat\Collection\Interfaces\WritableCollection;

class ReadOnlyCollectionTest extends PHPUnit_Framework_TestCase {
  public function testCollectionImplementsProperInterfaces() {
    $collection = new ReadOnlyCollection();
    
    $this->assertInstanceOf(Collection::class, $collection);
    $this->assertInstanceOf(IteratorAggregate::class, $collection);
    
    $this->assertNotInstanceOf(WritableCollection::class, $collection);
    $this->assertNotInstanceOf(ArrayAccess::class, $collection);
  }
  
  public function testConstructEmpty() {
    $collection = new ReadOnlyCollection();
    
    $this->assertEmpty($collection->all());
  }
  
  public function testConstructWithContent() {
    $values = ['key1' => 'val1', 'key2' => 'val2'];
    
    $collection = new ReadOnlyCollection($values);
    
    $this->assertSame($values, $collection->all());
  }
  
  public function testInstantiator() {
    $collection = new ReadOnlyCollection();
    
    $this->assertInstanceOf(ReadOnlyCollection::class, $collection->__new([]));
  }
}
