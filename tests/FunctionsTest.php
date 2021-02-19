<?php

use PHPUnit\Framework\TestCase;
use function BapCat\Collection\collect;

class FunctionsTest extends TestCase {
  public function testCollect() {
    $collection = collect(['test']);
    
    $this->assertSame(['test'], $collection->toArray());
  }
}
