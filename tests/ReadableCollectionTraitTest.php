<?php

use BapCat\Collection\Exceptions\NoSuchKeyException;
use BapCat\Collection\Traits\ReadableCollectionTrait;

class ReadableCollectionTraitTest extends PHPUnit_Framework_TestCase {
  private $trait;
  
  public function setUp() {
    $this->trait = $this
      ->getMockBuilder(ReadableCollectionTrait::class)
      ->getMockForTrait()
    ;
    
    // The trait requires $collection and $lazy to be set...
    // We can just create them as public properties from the
    // outside for these tests because PHP is weird like that
    $this->trait->collection = [];
    $this->trait->lazy       = [];
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
  
  public function testAll() {
    $this->assertEmpty($this->trait->all());
    
    $this->trait->collection['key1'] = 'val1';
    $this->trait->collection['key2'] = 'val2';
    
    $this->assertSame([
      'key1' => 'val1',
      'key2' => 'val2'
    ], $this->trait->all());
  }
  
  public function testLazyAll() {
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
    ], $this->trait->all());
    
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
    ], $this->trait->keys());
  }
  
  public function testValues() {
    $this->trait->collection['key1'] = 'val1';
    $this->trait->collection['key2'] = 'val2';
    
    $this->assertSame([
      'val1',
      'val2'
    ], $this->trait->values());
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
    ], $this->trait->values());
    
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
}
