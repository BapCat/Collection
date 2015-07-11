<?php

use BapCat\Collection\ArrayAccessCollection;

class ArrayAccessCollectionTest extends PHPUnit_Framework_TestCase {
  public function testArrayAccess() {
    $collection = new ArrayAccessCollection();
    $collection['key'] = 'val1';
    $collection[] = 'val2';
    
    $this->assertTrue(isset($collection['key']));
    $this->assertEquals('val1', $collection['key']);
    $this->assertEquals('val2', $collection[0]);
    $this->assertEquals(2, $collection->size());
    
    unset($collection['key']);
    
    $this->assertEquals(1, $collection->size());
  }
}
