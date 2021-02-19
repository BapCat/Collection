<?php

namespace Traits;

use BapCat\Collection\Traits\IterableCollectionTrait;
use PHPUnit\Framework\TestCase;

class IterableCollectionTraitTest extends TestCase {
  private $trait;
  
  public function setUp(): void {
    $this->trait = $this->mockTrait([
      'key1' => 'val1',
      'key2' => 'val2',
      'key3' => 'val3',
    ]);
  }
  
  public function testIteration() {
    $results = [];
    foreach($this->trait->getIterator() as $key => $val) {
      $results[$key] = $val;
    }
    
    $this->assertSame([
      'key1' => 'val1',
      'key2' => 'val2',
      'key3' => 'val3',
    ], $results);
  }
  
  private function mockTrait(array $values = []) {
    $trait = $this
      ->getMockBuilder(IterableCollectionTrait::class)
      ->onlyMethods(['toArray'])
      ->getMockForTrait()
    ;
    
    $trait
      ->method('toArray')
      ->willReturn($values)
    ;
    
    return $trait;
  }
}
