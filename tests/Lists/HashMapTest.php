<?php declare(strict_types=1);

use BapCat\Collection\Functions\BiConsumer;
use BapCat\Collection\Functions\BiFunction;
use BapCat\Collection\Functions\Func;
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

  public function testPutMapEntries(): void {
    $map = new HashMap();
    $map->put('key', 'val');

    $map2 = new HashMap();
    $map2->put('key1', 'val1');
    $map2->put('key2', 'val2');
    $map2->put('key3', 'val3');

    $map->putAll($map2);

    $this->assertSame('val', $map->get('key'));
    $this->assertSame('val1', $map->get('key1'));
    $this->assertSame('val2', $map->get('key2'));
    $this->assertSame('val3', $map->get('key3'));
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

  public function testComputeIfAbsent(): void {
    $map = new HashMap();

    $map->put('keyNull', null);
    $map->put('keyVal', 'old value');

    $funcNull = new class implements Func {
      public function apply($val) {
        return null;
      }
    };

    $funcVal = new class implements Func {
      public function apply($val) {
        return 'new value';
      }
    };

    $this->assertNull($map->computeIfAbsent('key', $funcNull));
    $this->assertNull($map->get('key'));
    $this->assertSame('new value', $map->computeIfAbsent('key', $funcVal));
    $this->assertSame('new value', $map->get('key'));

    $this->assertNull($map->computeIfAbsent('keyNull', $funcNull));
    $this->assertNull($map->get('keyNull'));
    $this->assertSame('new value', $map->computeIfAbsent('keyNull', $funcVal));
    $this->assertSame('new value', $map->get('keyNull'));

    $this->assertSame('old value', $map->computeIfAbsent('keyVal', $funcNull));
    $this->assertSame('old value', $map->get('keyVal'));
    $this->assertSame('old value', $map->computeIfAbsent('keyVal', $funcVal));
    $this->assertSame('old value', $map->get('keyVal'));
  }

  public function testComputeIfPresent(): void {
    $map = new HashMap();

    $map->put('keyNull', null);
    $map->put('keyVal', 'old value');

    $funcNull = new class implements BiFunction {
      public function apply($key, $val) {
        return null;
      }
    };

    $funcVal = new class implements BiFunction {
      public function apply($key, $val) {
        return 'new value';
      }
    };

    $this->assertNull($map->computeIfPresent('key', $funcVal));
    $this->assertNull($map->get('key'));
    $this->assertNull($map->computeIfPresent('key', $funcNull));
    $this->assertNull($map->get('key'));

    $this->assertNull($map->computeIfPresent('keyNull', $funcVal));
    $this->assertNull($map->get('keyNull'));
    $this->assertNull($map->computeIfPresent('keyNull', $funcNull));
    $this->assertNull($map->get('keyNull'));

    $this->assertSame('new value', $map->computeIfPresent('keyVal', $funcVal));
    $this->assertSame('new value', $map->get('keyVal'));
    $this->assertSame(null, $map->computeIfPresent('keyVal', $funcNull));
    $this->assertSame(null, $map->get('keyVal'));
  }

  public function testCompute(): void {
    $map = new HashMap();

    $map->put('key1', 'val1');
    $map->put('key2', 'val2');
    $map->put('key3', 'val3');
    $map->put('key4', 'val4');
    $map->put('key5', 'val5');

    $funcNull = new class implements BiFunction {
      public function apply($key, $val) {
        return null;
      }
    };

    $funcVal1 = new class implements BiFunction {
      public function apply($key, $val) {
        return $key;
      }
    };

    $funcVal2 = new class implements BiFunction {
      public function apply($key, $val) {
        return $key;
      }
    };

    for($i = 1; $i <= 5; $i++) {
      $key = "key$i";
      $this->assertSame($key, $map->compute($key, $funcVal1));
      $this->assertSame($key, $map->get($key));
    }

    for($i = 1; $i <= 5; $i++) {
      $key = "key$i";
      $this->assertNull($map->compute($key, $funcNull));
      $this->assertNull($map->get($key));
    }

    for($i = 1; $i <= 5; $i++) {
      $key = "key$i";
      $this->assertSame($key, $map->compute($key, $funcVal2));
      $this->assertSame($key, $map->get($key));
    }
  }

  public function testMerge(): void {
    $map = new HashMap();

    $map->put('key1', 'val1');
    $map->put('key2', 'val2');
    $map->put('key3', 'val3');
    $map->put('key4', 'val4');
    $map->put('key5', 'val5');

    $funcNull = new class implements BiFunction {
      public function apply($old, $new) {
        return null;
      }
    };

    $funcConcat = new class implements BiFunction {
      public function apply($old, $new) {
        return $old . $new;
      }
    };

    $funcSet = new class implements BiFunction {
      public function apply($old, $new) {
        return $new;
      }
    };

    for($i = 1; $i <= 5; $i++) {
      $key = "key$i";
      $val = "val{$i}a";
      $this->assertSame($val, $map->merge($key, 'a', $funcConcat));
      $this->assertSame($val, $map->get($key));
    }

    for($i = 1; $i <= 5; $i++) {
      $key = "key$i";
      $this->assertNull($map->merge($key, null, $funcNull));
      $this->assertNull($map->get($key));
    }

    for($i = 1; $i <= 5; $i++) {
      $key = "key$i";
      $val = "new";
      $this->assertSame($val, $map->merge($key, $val, $funcSet));
      $this->assertSame($val, $map->get($key));
    }

    for($i = 1; $i <= 5; $i++) {
      $key = "key$i";
      $val = "new";

      $map->put($key, null);

      $this->assertSame($val, $map->merge($key, $val, $funcSet));
      $this->assertSame($val, $map->get($key));
    }
  }

  public function testEach(): void {
    $map = new HashMap();

    $data = [];

    for($i = 1; $i <= 5; $i++) {
      $key = "key$i";
      $val = "val$i";
      $data[$key] = $val;
      $map->put($key, $val);
    }

    $consumer = new class($this, $data) implements BiConsumer {
      private $testCase;
      private $data;

      public function __construct(TestCase $testCase, array $data) {
        $this->testCase = $testCase;
        $this->data = $data;
      }

      public function accept($t, $u) : void {
        $this->testCase->assertSame($u, $this->data[$t]);
      }
    };

    $map->each($consumer);
  }

  public function testReplaceAll(): void {
    $map = new HashMap();

    $data = [];

    for($i = 1; $i <= 5; $i++) {
      $key = "key$i";
      $val = "val$i";
      $data[$key] = $val;
      $map->put($key, $val);
    }

    $replacer = new class implements BiFunction {
      public function apply($t, $u) {
        return $t;
      }
    };

    $map->replaceAll($replacer);

    foreach($data as $key => $val) {
      $this->assertSame($key, $map->get($key));
    }
  }
}
