<?php

use function BapCat\Collection\collect;

class FunctionsTest extends PHPUnit_Framework_TestCase {
  public function testCollect() {
    $collection = collect(['test']);
    
    $this->assertSame(['test'], $collection->all());
  }
}
