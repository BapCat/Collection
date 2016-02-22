<?php

use BapCat\Collection\Traits\IterableCollectionTrait;

class IterableCollectionTraitTest extends PHPUnit_Framework_TestCase {
  private $trait;
  
  public function setUp() {
    $this->trait = $this->mockTrait([
      'key1' => 'val1',
      'key2' => 'val2',
      'key3' => 'val3'
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
      'key3' => 'val3'
    ], $results);
  }
  
  private function mockTrait(array $values = []) {
    $trait = $this
      ->getMockBuilder(IterableCollectionTrait::class)
      ->setMethods(['all'])
      ->getMockForTrait()
    ;
    
    $trait
      ->method('all')
      ->willReturn($values)
    ;
    
    return $trait;
  }
}
