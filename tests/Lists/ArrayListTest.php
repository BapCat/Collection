<?php declare(strict_types=1);

use BapCat\Collection\IllegalStateException;
use BapCat\Collection\IndexOutOfBoundsException;
use BapCat\Collection\Lists\ArrayList;
use BapCat\Collection\NoSuchElementException;
use PHPUnit\Framework\TestCase;

class ArrayListTest extends TestCase {
  public function testSize(): void {
    $list = new ArrayList();
    $this->assertSame(0, $list->size());

    $list->add('a');
    $this->assertSame(1, $list->size());

    $list->add('b');
    $this->assertSame(2, $list->size());

    $list->add('c');
    $this->assertSame(3, $list->size());

    $list->add('a');
    $this->assertSame(4, $list->size());
  }

  public function testIsEmpty(): void {
    $list = new ArrayList();
    $this->assertTrue($list->isEmpty());

    $list->add('a');
    $this->assertFalse($list->isEmpty());
  }

  public function testIndexOf(): void {
    $list = new ArrayList();

    $this->assertSame(-1, $list->indexOf('a'));
    $this->assertSame(-1, $list->indexOf('b'));

    $list->add('a');

    $this->assertSame( 0, $list->indexOf('a'));
    $this->assertSame(-1, $list->indexOf('b'));

    $list->add('b');

    $this->assertSame(0, $list->indexOf('a'));
    $this->assertSame(1, $list->indexOf('b'));

    $list->add('a');

    $this->assertSame(0, $list->indexOf('a'));
    $this->assertSame(1, $list->indexOf('b'));
  }

  public function testLastIndexOf(): void {
    $list = new ArrayList();

    $this->assertSame(-1, $list->lastIndexOf('a'));
    $this->assertSame(-1, $list->lastIndexOf('b'));

    $list->add('a');

    $this->assertSame( 0, $list->lastIndexOf('a'));
    $this->assertSame(-1, $list->lastIndexOf('b'));

    $list->add('b');

    $this->assertSame(0, $list->lastIndexOf('a'));
    $this->assertSame(1, $list->lastIndexOf('b'));

    $list->add('a');

    $this->assertSame(2, $list->lastIndexOf('a'));
    $this->assertSame(1, $list->lastIndexOf('b'));
  }

  public function testContains(): void {
    $list = new ArrayList();

    $this->assertFalse($list->contains('a'));
    $this->assertFalse($list->contains('b'));

    $list->add('a');

    $this->assertTrue($list->contains('a'));
    $this->assertFalse($list->contains('b'));

    $list->add('b');

    $this->assertTrue($list->contains('a'));
    $this->assertTrue($list->contains('b'));
  }

  public function testToArray(): void {
    $list = new ArrayList();

    $this->assertSame([], $list->toArray());

    $list->add('a');
    $list->add('b');
    $list->add('c');
    $list->add('a');

    $this->assertSame(['a', 'b', 'c', 'a'], $list->toArray());

    $list->removeAt(2);

    $this->assertSame(['a', 'b', 'a'], $list->toArray());
  }

  public function testAddAndGet(): void {
    $list = new ArrayList();

    $input = ['a', 'b', 'c', 'd', 'e', 'a'];

    for($i = 0; $i < count($input); $i++) {
      $list->add($input[$i]);

      for($n = 0; $n < $list->size(); $n++) {
        $this->assertSame($input[$i], $list->get($i), "ArrayList#get returned incorrect value");
      }
    }
  }

  public function testAddAt(): void {
    $list = new ArrayList();

    $list->add('a');
    $list->add('b');
    $list->add('c');

    $list->addAt(1, 'x');
    $this->assertSame(['a', 'x', 'b', 'c'], $list->toArray());

    $list->addAt(1, 'y');
    $this->assertSame(['a', 'y', 'x', 'b', 'c'], $list->toArray());

    $list->addAt(5, 'z');
    $this->assertSame(['a', 'y', 'x', 'b', 'c', 'z'], $list->toArray());

    $this->expectException(IndexOutOfBoundsException::class);
    $list->addAt(1000, 'oob');
  }

  public function testAddAtNull(): void {
    $list = new ArrayList();

    $list->add('a');
    $list->add('b');
    $list->add('c');

    $list->addAt(1, null);
    $this->assertSame(['a', null, 'b', 'c'], $list->toArray());
  }

  public function testSet(): void {
    $list = new ArrayList();

    $list->add('a');
    $list->add('b');
    $list->add('c');
    $list->add('a');

    $this->assertSame(['a', 'b', 'c', 'a'], $list->toArray());
    $this->assertSame('b', $list->get(1));

    $list->set(1, 'a');
    $this->assertSame('a', $list->get(1));

    $list->set(2, 'z');
    $this->assertSame('z', $list->get(2));

    $this->assertSame(['a', 'a', 'z', 'a'], $list->toArray());

    $list->set(3, 'asdf');
    $this->assertSame('asdf', $list->get(3));

    $this->expectException(IndexOutOfBoundsException::class);
    $list->set(1000, 'oob');
  }

  public function testRemove(): void {
    $list = new ArrayList();

    $list->add('a');
    $list->add('b');
    $list->add('c');
    $list->add('a');

    $this->assertTrue($list->remove('a'));
    $this->assertSame(['b', 'c', 'a'], $list->toArray());
    $this->assertSame(3, $list->size());

    $this->assertTrue($list->remove('a'));
    $this->assertSame(['b', 'c'], $list->toArray());

    $this->assertFalse($list->remove('a'));
  }

  public function testRemoveAt(): void {
    $list = new ArrayList();

    $list->add('a');
    $list->add('b');
    $list->add('c');
    $list->add('a');

    $this->assertSame('c', $list->removeAt(2));
    $this->assertSame(['a', 'b', 'a'], $list->toArray());
    $this->assertSame(3, $list->size());

    $this->expectException(IndexOutOfBoundsException::class);
    $list->removeAt(1000);
  }

  public function testClear(): void {
    $list = new ArrayList();

    $list->add('a');
    $list->add('b');
    $list->add('c');
    $list->add('a');

    $list->clear();

    $this->assertSame(0, $list->size());
    $this->assertSame([], $list->toArray());
  }

  public function testForEach(): void {
    $list = new ArrayList();

    $elements = [];
    foreach($list as $element) {
      $elements[] = $element;
    }

    $this->assertSame([], $elements);

    $list->add('a');
    $list->add('b');
    $list->add('c');
    $list->add('a');

    $elements = [];
    foreach($list as $element) {
      $elements[] = $element;
    }

    $this->assertSame(['a', 'b', 'c', 'a'], $elements);

    $list->clear();

    $elements = [];
    foreach($list as $element) {
      $elements[] = $element;
    }

    $this->assertSame([], $elements);
  }

  public function testForEachWithIndices(): void {
    $list = new ArrayList();

    $list->add('a');
    $list->add('b');
    $list->add('c');
    $list->add('a');

    $elements = [];
    foreach($list as $index => $element) {
      $elements[$index] = $element;
    }

    $this->assertSame(['a', 'b', 'c', 'a'], $elements);
  }

  public function testIteration(): void {
    $list = new ArrayList();

    $elements = [];
    for($it = $list->iterator(); $it->hasNext(); $elements[] = $it->next()) { }

    $this->assertSame([], $elements);

    $list->add('a');
    $list->add('b');
    $list->add('c');
    $list->add('a');

    $elements = [];
    for($it = $list->iterator(); $it->hasNext(); $elements[] = $it->next()) { }

    $this->assertSame(['a', 'b', 'c', 'a'], $elements);

    $list->clear();

    $elements = [];
    for($it = $list->iterator(); $it->hasNext(); ) {
      $elements[] = $it->next();
    }

    $this->assertSame([], $elements);
  }

  public function testIterationNextWithNoElements(): void {
    $list = new ArrayList();

    $it = $list->iterator();

    $this->expectException(NoSuchElementException::class);
    $it->next();
  }

  public function testIterationRemove(): void {
    $list = new ArrayList();

    $list->add('a');
    $list->add('b');
    $list->add('c');
    $list->add('a');

    $removed = [];
    for($it = $list->iterator(); $it->hasNext(); ) {
      $removed[] = $it->next();
      $it->remove();
    }

    $this->assertSame(['a', 'b', 'c', 'a'], $removed, 'Failed to remove elements. Removed: ' . var_export($removed, true));
    $this->assertSame([], $list->toArray());
    $this->assertSame(0, $list->size());
  }

  public function testIterationRemoveWithNoElements(): void {
    $list = new ArrayList();

    $it = $list->iterator();

    $this->expectException(IllegalStateException::class);
    $it->remove();
  }

  public function testListIteration(): void {
    $list = new ArrayList();

    $list->add('a');
    $list->add('b');
    $list->add('c');

    $it = $list->listIterator();

    $this->assertFalse($it->hasPrevious());
    $this->assertTrue($it->hasNext());
    $this->assertSame(0, $it->nextIndex());
    $this->assertSame(-1, $it->previousIndex());
    $this->assertSame('a', $it->next());

    $this->assertTrue($it->hasPrevious());
    $this->assertTrue($it->hasNext());
    $this->assertSame(1, $it->nextIndex());
    $this->assertSame(0, $it->previousIndex());
    $this->assertSame('b', $it->next());

    $this->assertTrue($it->hasPrevious());
    $this->assertTrue($it->hasNext());
    $this->assertSame(2, $it->nextIndex());
    $this->assertSame(1, $it->previousIndex());
    $this->assertSame('c', $it->next());

    $this->assertTrue($it->hasPrevious());
    $this->assertFalse($it->hasNext());
    $this->assertSame(3, $it->nextIndex());
    $this->assertSame(2, $it->previousIndex());
    $this->assertSame('c', $it->previous());

    $this->assertTrue($it->hasPrevious());
    $this->assertTrue($it->hasNext());
    $this->assertSame(2, $it->nextIndex());
    $this->assertSame(1, $it->previousIndex());
    $this->assertSame('b', $it->previous());

    $this->assertTrue($it->hasPrevious());
    $this->assertTrue($it->hasNext());
    $this->assertSame(1, $it->nextIndex());
    $this->assertSame(0, $it->previousIndex());
    $this->assertSame('a', $it->previous());

    $this->assertFalse($it->hasPrevious());
    $this->assertTrue($it->hasNext());
    $this->assertSame(0, $it->nextIndex());
    $this->assertSame(-1, $it->previousIndex());
  }

  public function testListIterationNextWithNoElements(): void {
    $list = new ArrayList();

    $this->expectException(NoSuchElementException::class);
    $list->listIterator()->next();
  }

  public function testListIterationPreviousWithNoElements(): void {
    $list = new ArrayList();

    $this->expectException(NoSuchElementException::class);
    $list->listIterator()->previous();
  }

  public function testListIteratorSet(): void {
    $list = new ArrayList();
    $list->add('a');
    $list->add('b');
    $list->add('c');

    $it = $list->listIterator();

    $it->next();
    $it->next();
    $it->set('x');

    $this->assertSame(['a', 'x', 'c'], $list->toArray());
  }

  public function testListIteratorSetWhileEmpty(): void {
    $list = new ArrayList();
    $it = $list->listIterator();

    $this->expectException(IllegalStateException::class);
    $it->set('a');
  }

  public function testListIteratorAddToEmpty(): void {
    $list = new ArrayList();
    $it = $list->listIterator();
    $it->add('a');

    $this->assertSame(['a'], $list->toArray());
  }

  public function testListIteratorAddToMiddle(): void {
    $list = new ArrayList();
    $list->add('a');
    $list->add('b');

    $it = $list->listIterator();
    $it->next();
    $it->add('x');

    $this->assertSame(['a', 'x', 'b'], $list->toArray());
  }

  public function testListIteratorAddToEnd(): void {
    $list = new ArrayList();
    $list->add('a');
    $list->add('b');

    $it = $list->listIterator();
    $it->next();
    $it->next();
    $it->add('x');

    $this->assertSame(['a', 'b', 'x'], $list->toArray());
  }

  public function testListIteratorWithIndex(): void {
    $list = new ArrayList();
    $list->add('a');
    $list->add('b');
    $list->add('c');

    $it = $list->listIterator(1);

    $this->assertTrue($it->hasNext());
    $this->assertTrue($it->hasPrevious());
    $this->assertSame('b', $it->next());
  }

  public function testListIteratorWithIndexOutOfBounds(): void {
    $list = new ArrayList();

    $this->expectException(IndexOutOfBoundsException::class);
    $list->listIterator(100);
  }
}
