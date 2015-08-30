<?php

use BapCat\Collection\ReadOnlyCollection;

class ReadableCollectionTest extends PHPUnit_Framework_TestCase {
  public function testCollection() {
    $collection = new ReadOnlyCollection(['key' => 'val']);
    
    $this->assertTrue($collection->has('key'));
  }
}
