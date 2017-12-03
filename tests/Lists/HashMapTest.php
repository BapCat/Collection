<?php declare(strict_types=1);

use BapCat\Collection\Maps\HashMap;
use PHPUnit\Framework\TestCase;

class HashMapTest extends TestCase {
  public function testGetOnEmpty(): void {
    $map = new HashMap();

    $this->assertNull($map->get('key'));
  }

  public function testGetOrDefault(): void {
    $map = new HashMap();

    $map->put('key1', 'val1');

    $this->assertSame('val1', $map->get('key1'));
    $this->assertNull($map->get('key2'));
    $this->assertSame('val1', $map->getOrDefault('key1', 'def'));
    $this->assertSame('def', $map->getOrDefault('key2', 'def'));
  }

  public function testPut(): void {
    $map = new HashMap();

    $keys = [
      'key',
      1,
      new stdClass(),
      [],
      null,
    ];

    $vals = ['a', 'b', 'c', 'd', 'e'];

    foreach($keys as $index => $key) {
      $this->assertNull($map->put($key, $vals[$index]));
      $this->assertSame($vals[$index], $map->get($key));
    }

    $this->assertSame(5, $map->size());
  }

  public function testPutOverwrite(): void {
    $map = new HashMap();

    $this->assertNull($map->put('key', 'val'));
    $this->assertSame(1, $map->size());
    $this->assertSame('val', $map->get('key'));

    $this->assertSame('val', $map->put('key', 'new'));
    $this->assertSame(1, $map->size());
    $this->assertSame('new', $map->get('key'));
  }

  public function testPutIfAbsent(): void {
    $map = new HashMap();

    $this->assertNull($map->put('key', 'val'));
    $this->assertSame(1, $map->size());
    $this->assertSame('val', $map->get('key'));

    $this->assertSame('val', $map->putIfAbsent('key', 'new'));
    $this->assertSame(1, $map->size());
    $this->assertSame('val', $map->get('key'));
  }

  public function testReplace(): void {
    $map = new HashMap();

    $map->put('key1', 'val1');

    $this->assertSame('val1', $map->replace('key1', 'new'));
    $this->assertSame('new', $map->get('key1'));
    $this->assertSame(1, $map->size());

    $this->assertNull($map->replace('key2', 'val2'));
    $this->assertNull($map->get('key2'));
    $this->assertSame(1, $map->size());
  }

  public function testRemove(): void {
    $map = new HashMap();

    $map->put('key1', 'val1');
    $map->put('key2', 'val2');
    $map->put('key3', 'val3');
    $map->put('key4', 'val4');

    $this->assertSame(4, $map->size());
    $this->assertSame('val3', $map->remove('key3'));
    $this->assertSame(3, $map->size());
  }

  public function testRemoveOnEmpty(): void {
    $map = new HashMap();

    $this->assertNull($map->remove('key3'));
    $this->assertSame(0, $map->size());
  }

  public function testClear(): void {
    $map = new HashMap();

    $map->put('key1', 'val1');
    $map->put('key2', 'val2');
    $map->put('key3', 'val3');
    $map->put('key4', 'val4');

    $this->assertSame(4, $map->size());
    $map->clear();
    $this->assertSame(0, $map->size());
  }

  public function testIsEmpty(): void {
    $map = new HashMap();

    $this->assertTrue($map->isEmpty());
    $map->put('a', 'a');
    $this->assertFalse($map->isEmpty());
    $map->remove('a');
    $this->assertTrue($map->isEmpty());
  }

  public function testContainsKey(): void {
    $map = new HashMap();

    $map->put('key1', 'val1');
    $map->put('key2', 'val2');
    $map->put('key3', 'val3');
    $map->put('key4', 'val4');

    $this->assertTrue($map->containsKey('key1'));
    $this->assertTrue($map->containsKey('key2'));
    $this->assertTrue($map->containsKey('key3'));
    $this->assertTrue($map->containsKey('key4'));
    $this->assertFalse($map->containsKey('x'));
  }

  public function testContainsValue(): void {
    $map = new HashMap();

    $map->put('key1', 'val1');
    $map->put('key2', 'val2');
    $map->put('key3', 'val3');
    $map->put('key4', 'val4');

    $this->assertTrue($map->containsValue('val1'));
    $this->assertTrue($map->containsValue('val2'));
    $this->assertTrue($map->containsValue('val3'));
    $this->assertTrue($map->containsValue('val4'));
    $this->assertFalse($map->containsValue('x'));
  }
}
