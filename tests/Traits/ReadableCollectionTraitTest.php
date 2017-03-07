<?php

use BapCat\Collection\Exceptions\NoSuchKeyException;
use BapCat\Collection\Interfaces\Collection;
use BapCat\Collection\Traits\ReadableCollectionTrait;

class ReadableCollectionTraitTest extends PHPUnit_Framework_TestCase {
  private $trait;
  
  public function setUp() {
    $this->trait = $this->mockTrait();
  }
  
  public function testHas() {
    $this->assertFalse($this->trait->has('nope'));
    
    $this->trait->collection['nope'] = 'yep';
    
    $this->assertTrue($this->trait->has('nope'));
  }
  
  public function testBasicGetWithValidKeyAndNoDefaultValue() {
    $this->trait->collection['key'] = 'val';
    
    $this->assertSame('val', $this->trait->get('key'));
  }
  
  public function testBasicGetWithValidKeyAndDefaultValue() {
    $this->trait->collection['key'] = 'val';
    
    $this->assertSame('val', $this->trait->get('key', 'default'));
  }
  
  public function testBasicGetWithInvalidKeyAndNoDefaultValue() {
    $this->setExpectedException(NoSuchKeyException::class);
    $this->assertSame('val', $this->trait->get('key'));
  }
  
  public function testBasicGetWithInvalidKeyAndDefaultValue() {
    $this->assertSame('default', $this->trait->get('key', 'default'));
  }
  
  public function testLazyGetWithValidKeyAndNoDefaultValue() {
    $this->trait->collection['key'] = function($key) {
      return 'val';
    };
    
    $this->trait->lazy['key'] = 'key';
    
    $this->assertSame('val', $this->trait->get('key'));
    
    $this->assertEmpty($this->trait->lazy);
  }
  
  public function testLazyGetWithValidKeyAndDefaultValue() {
    $this->trait->collection['key'] = function($key) {
      return 'val';
    };
    
    $this->trait->lazy['key'] = 'key';
    
    $this->assertSame('val', $this->trait->get('key', 'default'));
    
    $this->assertEmpty($this->trait->lazy);
  }
  
  public function testFirstWithEmptyCollectionThrowsException() {
    $this->setExpectedException(NoSuchKeyException::class);
    $this->trait->first();
  }
  
  public function testFirst() {
    $this->trait->collection['key1'] = 'val1';
    $this->trait->collection['key2'] = 'val2';
    
    $this->assertSame('val1', $this->trait->first());
  }
  
  public function testLazyFirst() {
    $this->trait->collection['key'] = function($key) {
      return 'val';
    };
    
    $this->trait->lazy['key'] = 'key';
    
    $this->assertSame('val', $this->trait->first());
    
    $this->assertEmpty($this->trait->lazy);
  }
  
  public function testLastWithEmptyCollectionThrowsException() {
    $this->setExpectedException(NoSuchKeyException::class);
    $this->trait->last();
  }
  
  public function testLast() {
    $this->trait->collection['key1'] = 'val1';
    $this->trait->collection['key2'] = 'val2';
    
    $this->assertSame('val2', $this->trait->last());
  }
  
  public function testLazyLast() {
    $this->trait->collection['key'] = function($key) {
      return 'val';
    };
    
    $this->trait->lazy['key'] = 'key';
    
    $this->assertSame('val', $this->trait->last());
    
    $this->assertEmpty($this->trait->lazy);
  }
  
  public function testToArray() {
    $this->assertEmpty($this->trait->toArray());
    
    $this->trait->collection['key1'] = 'val1';
    $this->trait->collection['key2'] = 'val2';
    
    $this->assertSame([
      'key1' => 'val1',
      'key2' => 'val2'
    ], $this->trait->toArray());
  }
  
  public function testLazyToArray() {
    $this->trait->collection['key1'] = function($key) {
      return 'val1';
    };
    
    $this->trait->collection['key2'] = function($key) {
      return 'val2';
    };
    
    $this->trait->lazy['key1'] = 'key1';
    $this->trait->lazy['key2'] = 'key2';
    
    $this->assertSame([
      'key1' => 'val1',
      'key2' => 'val2'
    ], $this->trait->toArray());
    
    $this->assertEmpty($this->trait->lazy);
  }
  
  public function testEach() {
    $this->trait->collection['key1'] = 'val1';
    $this->trait->collection['key2'] = 'val2';
    
    $each = [];
    $this->trait->each(function($value, $key) use(&$each) {
      $each[$key] = $value;
    });
    
    $this->assertSame([
      'key1' => 'val1',
      'key2' => 'val2'
    ], $each);
  }
  
  public function testLazyEach() {
    $this->trait->collection['key1'] = function($key) {
      return 'val1';
    };
    
    $this->trait->collection['key2'] = function($key) {
      return 'val2';
    };
    
    $this->trait->lazy['key1'] = 'key1';
    $this->trait->lazy['key2'] = 'key2';
    
    $each = [];
    $this->trait->each(function($value, $key) use(&$each) {
      $each[$key] = $value;
    });
    
    $this->assertSame([
      'key1' => 'val1',
      'key2' => 'val2'
    ], $each);
    
    $this->assertEmpty($this->trait->lazy);
  }
  
  public function testKeys() {
    $this->trait->collection['key1'] = 'val1';
    $this->trait->collection['key2'] = 'val2';
    
    $this->assertSame([
      'key1',
      'key2'
    ], $this->trait->keys()->toArray());
  }
  
  public function testValues() {
    $this->trait->collection['key1'] = 'val1';
    $this->trait->collection['key2'] = 'val2';
    
    $this->assertSame([
      'val1',
      'val2'
    ], $this->trait->values()->toArray());
  }
  
  public function testLazyValues() {
    $this->trait->collection['key1'] = function($key) {
      return 'val1';
    };
    
    $this->trait->collection['key2'] = function($key) {
      return 'val2';
    };
    
    $this->trait->lazy['key1'] = 'key1';
    $this->trait->lazy['key2'] = 'key2';
    
    $this->assertSame([
      'val1',
      'val2'
    ], $this->trait->values()->toArray());
    
    $this->assertEmpty($this->trait->lazy);
  }
  
  public function testSize() {
    $this->assertSame(0, $this->trait->size());
    
    $this->trait->collection['key1'] = 'val1';
    $this->assertSame(1, $this->trait->size());
    
    $this->trait->collection['key2'] = 'val2';
    $this->assertSame(2, $this->trait->size());
    
    unset($this->trait->collection['key1']);
    $this->assertSame(1, $this->trait->size());
  }
  
  public function testMerge() {
    $collection = $this->mockCollection(['key2' => 'val2']);
    
    $this->trait->collection['key1'] = 'val1';
    
    $merged = $this->trait->merge($collection);
    
    $this->assertSame([
      'key1' => 'val1',
      'key2' => 'val2'
    ], $merged->toArray());
  }
  
  public function testDistinct() {
    $this->trait->collection['key1'] = 'val1';
    $this->trait->collection['key2'] = 'val1';
    $this->trait->collection['key3'] = 'val2';
    
    $distinct = $this->trait->distinct();
    
    $this->assertSame([
      'key1' => 'val1',
      'key3' => 'val2'
    ], $distinct->toArray());
  }
  
  public function testReverse() {
    $this->trait->collection['key1'] = 'val1';
    $this->trait->collection['key2'] = 'val2';
    $this->trait->collection['key3'] = 'val3';
    
    $reversed = $this->trait->reverse();
    
    $this->assertSame([
      'key3' => 'val3',
      'key2' => 'val2',
      'key1' => 'val1'
    ], $reversed->toArray());
  }
  
  public function testFlip() {
    $this->trait->collection['key1'] = 'val1';
    $this->trait->collection['key2'] = 'val2';
    $this->trait->collection['key3'] = 'val3';
    
    $reversed = $this->trait->flip();
    
    $this->assertSame([
      'val1' => 'key1',
      'val2' => 'key2',
      'val3' => 'key3'
    ], $reversed->toArray());
  }
  
  public function testSlicePositiveOffset() {
    $this->trait->collection['key1'] = 'val1';
    $this->trait->collection['key2'] = 'val2';
    $this->trait->collection['key3'] = 'val3';
    
    $slice = $this->trait->slice(2);
    
    $this->assertSame([
      'key3' => 'val3'
    ], $slice->toArray());
  }
  
  public function testSlicePositiveOffsetWithPositiveLength() {
    $this->trait->collection['key1'] = 'val1';
    $this->trait->collection['key2'] = 'val2';
    $this->trait->collection['key3'] = 'val3';
    
    $slice = $this->trait->slice(1, 1);
    
    $this->assertSame([
      'key2' => 'val2'
    ], $slice->toArray());
  }
  
  public function testSlicePositiveOffsetWithNegativeLength() {
    $this->trait->collection['key1'] = 'val1';
    $this->trait->collection['key2'] = 'val2';
    $this->trait->collection['key3'] = 'val3';
    
    $slice = $this->trait->slice(0, -1);
    
    $this->assertSame([
      'key1' => 'val1',
      'key2' => 'val2'
    ], $slice->toArray());
  }
  
  public function testSliceNegativeOffset() {
    $this->trait->collection['key1'] = 'val1';
    $this->trait->collection['key2'] = 'val2';
    $this->trait->collection['key3'] = 'val3';
    
    $slice = $this->trait->slice(-2);
    
    $this->assertSame([
      'key2' => 'val2',
      'key3' => 'val3'
    ], $slice->toArray());
  }
  
  public function testSliceNegativeOffsetWithPositiveLength() {
    $this->trait->collection['key1'] = 'val1';
    $this->trait->collection['key2'] = 'val2';
    $this->trait->collection['key3'] = 'val3';
    
    $slice = $this->trait->slice(-3, 2);
    
    $this->assertSame([
      'key1' => 'val1',
      'key2' => 'val2'
    ], $slice->toArray());
  }
  
  public function testSliceNegativeOffsetWithNegativeLength() {
    $this->trait->collection['key1'] = 'val1';
    $this->trait->collection['key2'] = 'val2';
    $this->trait->collection['key3'] = 'val3';
    
    $slice = $this->trait->slice(-2, -1);
    
    $this->assertSame([
      'key2' => 'val2'
    ], $slice->toArray());
  }
  
  public function testSplicePositiveOffsetPositiveLengthNoReplacement() {
    $this->trait->collection['key1'] = 'val1';
    $this->trait->collection['key2'] = 'val2';
    $this->trait->collection['key3'] = 'val3';
    
    $splice = $this->trait->splice(1, 1);
    
    $this->assertSame([
      'key1' => 'val1',
      'key3' => 'val3'
    ], $splice->toArray());
  }
  
  public function testSplicePositiveOffsetNegativeLengthNoReplacement() {
    $this->trait->collection['key1'] = 'val1';
    $this->trait->collection['key2'] = 'val2';
    $this->trait->collection['key3'] = 'val3';
    
    $splice = $this->trait->splice(1, -1);
    
    $this->assertSame([
      'key1' => 'val1',
      'key3' => 'val3'
    ], $splice->toArray());
  }
  
  public function testSpliceNegativeOffsetPositiveLengthNoReplacement() {
    $this->trait->collection['key1'] = 'val1';
    $this->trait->collection['key2'] = 'val2';
    $this->trait->collection['key3'] = 'val3';
    
    $splice = $this->trait->splice(-2, 1);
    
    $this->assertSame([
      'key1' => 'val1',
      'key3' => 'val3'
    ], $splice->toArray());
  }
  
  public function testSpliceNegativeOffsetNegativeLengthNoReplacement() {
    $this->trait->collection['key1'] = 'val1';
    $this->trait->collection['key2'] = 'val2';
    $this->trait->collection['key3'] = 'val3';
    
    $splice = $this->trait->splice(-2, -1);
    
    $this->assertSame([
      'key1' => 'val1',
      'key3' => 'val3'
    ], $splice->toArray());
  }
  
  public function testSplicePositiveOffsetPositiveLengthWithReplacement() {
    $this->trait->collection['key1'] = 'val1';
    $this->trait->collection['key2'] = 'val2';
    $this->trait->collection['key3'] = 'val3';
    
    $replacement = $this->mockCollection(['newkey' => 'newval']);
    
    $splice = $this->trait->splice(1, 1, $replacement);
    
    $this->assertSame([
      'key1' => 'val1',
      0 => 'newval',
      'key3' => 'val3'
    ], $splice->toArray());
  }
  
  public function testSplicePositiveOffsetNegativeLengthWithReplacement() {
    $this->trait->collection['key1'] = 'val1';
    $this->trait->collection['key2'] = 'val2';
    $this->trait->collection['key3'] = 'val3';
    
    $replacement = $this->mockCollection(['newkey' => 'newval']);
    
    $splice = $this->trait->splice(1, -1, $replacement);
    
    $this->assertSame([
      'key1' => 'val1',
      0 => 'newval',
      'key3' => 'val3'
    ], $splice->toArray());
  }
  
  public function testSpliceNegativeOffsetPositiveLengthWithReplacement() {
    $this->trait->collection['key1'] = 'val1';
    $this->trait->collection['key2'] = 'val2';
    $this->trait->collection['key3'] = 'val3';
    
    $replacement = $this->mockCollection(['newkey' => 'newval']);
    
    $splice = $this->trait->splice(-2, 1, $replacement);
    
    $this->assertSame([
      'key1' => 'val1',
      0 => 'newval',
      'key3' => 'val3'
    ], $splice->toArray());
  }
  
  public function testSpliceNegativeOffsetNegativeLengthWithReplacement() {
    $this->trait->collection['key1'] = 'val1';
    $this->trait->collection['key2'] = 'val2';
    $this->trait->collection['key3'] = 'val3';
    
    $replacement = $this->mockCollection(['newkey' => 'newval']);
    
    $splice = $this->trait->splice(-2, -1, $replacement);
    
    $this->assertSame([
      'key1' => 'val1',
      0 => 'newval',
      'key3' => 'val3'
    ], $splice->toArray());
  }
  
  public function testFilter() {
    $this->trait->collection[0] = 0;
    $this->trait->collection['a'] = 1;
    $this->trait->collection[1] = 'a';
    $this->trait->collection['b'] = 'b';
    
    $filtered = $this->trait->filter(function($key, $val) {
      return is_numeric($key) || is_numeric($val);
    });
    
    $this->assertSame([
      0 => 0,
      'a' => 1,
      1 => 'a'
    ], $filtered->toArray());
  }
  
  public function testFilterByKeys() {
    $this->trait->collection[0] = 0;
    $this->trait->collection['a'] = 1;
    $this->trait->collection[1] = 'a';
    $this->trait->collection['b'] = 'b';
    
    $filtered = $this->trait->filterByKeys('is_numeric');
    
    $this->assertSame([
      0 => 0,
      1 => 'a'
    ], $filtered->toArray());
  }
  
  public function testFilterByValues() {
    $this->trait->collection[0] = 0;
    $this->trait->collection['a'] = 1;
    $this->trait->collection[1] = 'a';
    $this->trait->collection['b'] = 'b';
    
    $filtered = $this->trait->filterByValues('is_numeric');
    
    $this->assertSame([
      0 => 0,
      'a' => 1
    ], $filtered->toArray());
  }
  
  public function testMap() {
    $this->trait->collection[0] = 'a';
    $this->trait->collection[1] = 'b';
    $this->trait->collection[2] = 'c';
    $this->trait->collection[3] = 'd';
    
    $mapped = $this->trait->map('strtoupper');
    
    $this->assertSame([
      0 => 'A',
      1 => 'B',
      2 => 'C',
      3 => 'D'
    ], $mapped->toArray());
  }
  
  public function testJoin() {
    $this->trait->collection[0] = 'a';
    $this->trait->collection[1] = 'b';
    $this->trait->collection[2] = 'c';
    $this->trait->collection[3] = 'd';
    
    $this->assertSame('abcd', $this->trait->join());
    $this->assertSame('a-b-c-d', $this->trait->join('-'));
  }
  
  private function mockTrait(array $values = []) {
    $trait = $this
      ->getMockBuilder(ReadableCollectionTrait::class)
      ->setMethods(['__new'])
      ->getMockForTrait()
    ;
    
    $trait
      ->method('__new')
      ->will($this->returnCallback(function(array $values) {
        return $this->mockTrait($values);
      }))
    ;
    
    // The trait requires $collection and $lazy to be set...
    // We can just create them as public properties from the
    // outside for these tests because PHP is weird like that
    $trait->collection = $values;
    $trait->lazy       = [];
    
    return $trait;
  }
  
  private function mockCollection(array $values = []) {
    $collection = $this
      ->getMockBuilder(Collection::class)
      ->setMethods(['toArray'])
      ->getMockForAbstractClass()
    ;
    
    $collection
      ->method('toArray')
      ->willReturn($values)
    ;
    
    return $collection;
  }
}
