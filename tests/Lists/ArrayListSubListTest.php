<?php declare(strict_types=1);

use BapCat\Collection\IllegalArgumentException;
use BapCat\Collection\IllegalStateException;
use BapCat\Collection\IndexOutOfBoundsException;
use BapCat\Collection\Lists\ArrayList;
use BapCat\Collection\NoSuchElementException;
use PHPUnit\Framework\TestCase;

class ArrayListSubListTest extends TestCase {
  public function testSubList(): void {
    $list = new ArrayList();
    $list->add('a');
    $list->add('b');
    $list->add('c');
    $list->add('d');
    $list->add('e');

    $subList = $list->subList(1, 4);

    $this->assertSame('b', $subList->get(0));
    $this->assertSame('c', $subList->get(1));
    $this->assertSame('d', $subList->get(2));

    $this->assertSame(['b', 'c', 'd'], $subList->toArray());
    $this->assertSame(3, $subList->size());
  }

  public function testSubListOutOfBounds(): void {
    $list = new ArrayList();

    $this->expectException(IndexOutOfBoundsException::class);
    $list->subList(1, 4);
  }

  public function testSubListInvalidArgs(): void {
    $list = new ArrayList();
    $list->add('a');

    $this->expectException(IllegalArgumentException::class);
    $list->subList(1, 0);
  }

  public function testSubListSet(): void {
    $list = new ArrayList();
    $list->add('a');
    $list->add('b');
    $list->add('c');
    $list->add('d');
    $list->add('e');

    $subList = $list->subList(1, 4);

    $subList->set(1, 'x');

    $this->assertSame('x', $subList->get(1));
    $this->assertSame('x', $list->get(2));
  }

  public function testSubListSetNegative(): void {
    $list = new ArrayList();
    $list->add('a');
    $list->add('b');
    $list->add('c');
    $list->add('d');
    $list->add('e');

    $subList = $list->subList(1, 4);

    $this->expectException(IndexOutOfBoundsException::class);
    $subList->set(-1, 'x');
  }

  public function testSubListSetOutOfBounds(): void {
    $list = new ArrayList();
    $list->add('a');
    $list->add('b');
    $list->add('c');
    $list->add('d');
    $list->add('e');

    $subList = $list->subList(1, 4);

    $this->expectException(IndexOutOfBoundsException::class);
    $subList->set(100, 'x');
  }

  public function testSubListAddAt(): void {
    $list = new ArrayList();
    $list->add('a');
    $list->add('b');
    $list->add('c');
    $list->add('d');
    $list->add('e');

    $subList = $list->subList(1, 4);

    $subList->addAt(1, 'x');

    $this->assertSame('x', $subList->get(1));
    $this->assertSame('x', $list->get(2));

    $this->assertSame(4, $subList->size());
    $this->assertSame(['b', 'x', 'c', 'd'], $subList->toArray());
    $this->assertSame(['a', 'b', 'x', 'c', 'd', 'e'], $list->toArray());
  }

  public function testSubListAddAtNegative(): void {
    $list = new ArrayList();
    $list->add('a');
    $list->add('b');
    $list->add('c');
    $list->add('d');
    $list->add('e');

    $subList = $list->subList(1, 4);

    $this->expectException(IndexOutOfBoundsException::class);
    $subList->addAt(-1, 'x');
  }

  public function testSubListAddAtOutOfBounds(): void {
    $list = new ArrayList();
    $list->add('a');
    $list->add('b');
    $list->add('c');
    $list->add('d');
    $list->add('e');

    $subList = $list->subList(1, 4);

    $this->expectException(IndexOutOfBoundsException::class);
    $subList->addAt(100, 'x');
  }

  public function testSubListRemoveAt(): void {
    $list = new ArrayList();
    $list->add('a');
    $list->add('b');
    $list->add('c');
    $list->add('d');
    $list->add('e');

    $subList = $list->subList(1, 4);

    $subList->removeAt(1);

    $this->assertSame('d', $subList->get(1));
    $this->assertSame('d', $list->get(2));

    $this->assertSame(2, $subList->size());
    $this->assertSame(['b', 'd'], $subList->toArray());
    $this->assertSame(['a', 'b', 'd', 'e'], $list->toArray());
  }

  public function testSubListRemoveAtNegative(): void {
    $list = new ArrayList();
    $list->add('a');
    $list->add('b');
    $list->add('c');
    $list->add('d');
    $list->add('e');

    $subList = $list->subList(1, 4);

    $this->expectException(IndexOutOfBoundsException::class);
    $subList->removeAt(-1);
  }

  public function testSubListRemoveAtOutOfBounds(): void {
    $list = new ArrayList();
    $list->add('a');
    $list->add('b');
    $list->add('c');
    $list->add('d');
    $list->add('e');

    $subList = $list->subList(1, 4);

    $this->expectException(IndexOutOfBoundsException::class);
    $subList->removeAt(100);
  }

  public function testClear(): void {
    $list = new ArrayList();
    $list->add('a');
    $list->add('b');
    $list->add('c');
    $list->add('d');
    $list->add('e');

    $subList = $list->subList(1, 4);

    $subList->clear();

    $this->assertSame(0, $subList->size());
    $this->assertSame([], $subList->toArray());
    $this->assertSame(2, $list->size());
    $this->assertSame(['a', 'e'], $list->toArray());
  }

  public function testForEach(): void {
    $list = new ArrayList();

    $list->add('a');
    $list->add('b');
    $list->add('c');
    $list->add('d');
    $list->add('e');

    $subList = $list->subList(1, 4);

    $elements = [];
    foreach($subList as $element) {
      $elements[] = $element;
    }

    $this->assertSame(['b', 'c', 'd'], $elements);
  }

  public function testForEachWithIndices(): void {
    $list = new ArrayList();

    $list->add('a');
    $list->add('b');
    $list->add('c');
    $list->add('d');
    $list->add('e');

    $subList = $list->subList(1, 4);

    $elements = [];
    foreach($subList as $index => $element) {
      $elements[$index] = $element;
    }

    $this->assertSame(['b', 'c', 'd'], $elements);
  }

  public function testIteration(): void {
    $list = new ArrayList();

    $list->add('a');
    $list->add('b');
    $list->add('c');
    $list->add('d');
    $list->add('e');

    $subList = $list->subList(1, 4);

    $elements = [];
    for($it = $subList->iterator(); $it->hasNext(); $elements[] = $it->next()) { }

    $this->assertSame(['b', 'c', 'd'], $elements);
  }

  public function testIterationNextWithNoElements(): void {
    $list = new ArrayList();

    $it = $list->subList(0, 0)->iterator();

    $this->expectException(NoSuchElementException::class);
    $it->next();
  }

  public function testIterationRemove(): void {
    $list = new ArrayList();

    $list->add('a');
    $list->add('b');
    $list->add('c');
    $list->add('d');
    $list->add('e');

    $subList = $list->subList(1, 4);

    $removed = [];
    for($it = $subList->iterator(); $it->hasNext(); ) {
      $removed[] = $it->next();
      $it->remove();
    }

    $this->assertSame(['b', 'c', 'd'], $removed, 'Failed to remove elements. Removed: ' . var_export($removed, true));
    $this->assertSame([], $subList->toArray());
    $this->assertSame(0, $subList->size());
    $this->assertSame(['a', 'e'], $list->toArray());
    $this->assertSame(2, $list->size());
  }

  public function testIterationRemoveWithNoElements(): void {
    $list = new ArrayList();

    $it = $list->subList(0, 0)->iterator();

    $this->expectException(IllegalStateException::class);
    $it->remove();
  }

  public function testIterationRemoveFailsAfterAdd(): void {
    $list = new ArrayList();
    $list->add('a');

    $it = $list->subList(0, 1)->iterator();
    $it->next();
    $it->add('b');

    $this->expectException(IllegalStateException::class);
    $it->remove();
  }

  public function testIterationRemoveFailsAfterRemove(): void {
    $list = new ArrayList();
    $list->add('a');
    $list->add('b');

    $it = $list->subList(0, 2)->iterator();
    $it->next();
    $it->remove();

    $this->expectException(IllegalStateException::class);
    $it->remove();
  }

  public function testListIteration(): void {
    $list = new ArrayList();

    $list->add('a');
    $list->add('b');
    $list->add('c');
    $list->add('d');
    $list->add('e');

    $subList = $list->subList(1, 4);

    $it = $subList->listIterator();

    $this->assertFalse($it->hasPrevious());
    $this->assertTrue($it->hasNext());
    $this->assertSame(0, $it->nextIndex());
    $this->assertSame(-1, $it->previousIndex());
    $this->assertSame('b', $it->next());

    $this->assertTrue($it->hasPrevious());
    $this->assertTrue($it->hasNext());
    $this->assertSame(1, $it->nextIndex());
    $this->assertSame(0, $it->previousIndex());
    $this->assertSame('c', $it->next());

    $this->assertTrue($it->hasPrevious());
    $this->assertTrue($it->hasNext());
    $this->assertSame(2, $it->nextIndex());
    $this->assertSame(1, $it->previousIndex());
    $this->assertSame('d', $it->next());

    $this->assertTrue($it->hasPrevious());
    $this->assertFalse($it->hasNext());
    $this->assertSame(3, $it->nextIndex());
    $this->assertSame(2, $it->previousIndex());
    $this->assertSame('d', $it->previous());

    $this->assertTrue($it->hasPrevious());
    $this->assertTrue($it->hasNext());
    $this->assertSame(2, $it->nextIndex());
    $this->assertSame(1, $it->previousIndex());
    $this->assertSame('c', $it->previous());

    $this->assertTrue($it->hasPrevious());
    $this->assertTrue($it->hasNext());
    $this->assertSame(1, $it->nextIndex());
    $this->assertSame(0, $it->previousIndex());
    $this->assertSame('b', $it->previous());

    $this->assertFalse($it->hasPrevious());
    $this->assertTrue($it->hasNext());
    $this->assertSame(0, $it->nextIndex());
    $this->assertSame(-1, $it->previousIndex());
  }

  public function testListIterationNextWithNoElements(): void {
    $list = new ArrayList();

    $this->expectException(NoSuchElementException::class);
    $list->subList(0, 0)->listIterator()->next();
  }

  public function testListIterationPreviousWithNoElements(): void {
    $list = new ArrayList();

    $this->expectException(NoSuchElementException::class);
    $list->subList(0, 0)->listIterator()->previous();
  }

  public function testListIteratorSet(): void {
    $list = new ArrayList();
    $list->add('a');
    $list->add('b');
    $list->add('c');
    $list->add('d');
    $list->add('e');

    $subList = $list->subList(1, 4);

    $it = $subList->listIterator();

    $this->assertSame('b', $it->next());
    $this->assertSame('c', $it->next());
    $it->set('x');

    $this->assertSame(['b', 'x', 'd'], $subList->toArray());
    $this->assertSame(['a', 'b', 'x', 'd', 'e'], $list->toArray());
  }

  public function testListIteratorSetWhileEmpty(): void {
    $list = new ArrayList();
    $it = $list->subList(0, 0)->listIterator();

    $this->expectException(IllegalStateException::class);
    $it->set('a');
  }

  public function testListIteratorAddToEmpty(): void {
    $list = new ArrayList();
    $subList = $list->subList(0, 0);
    $it = $subList->listIterator();
    $it->add('a');

    $this->assertSame(['a'], $subList->toArray());
    $this->assertSame(['a'], $list->toArray());
  }

  public function testListIteratorAddToMiddle(): void {
    $list = new ArrayList();
    $list->add('a');
    $list->add('b');
    $list->add('c');
    $list->add('d');

    $subList = $list->subList(1, 3);

    $it = $subList->listIterator();
    $it->next();
    $it->add('x');

    $this->assertSame(['b', 'x', 'c'], $subList->toArray());
    $this->assertSame(['a', 'b', 'x', 'c', 'd'], $list->toArray());
  }

  public function testListIteratorAddToEnd(): void {
    $list = new ArrayList();
    $list->add('a');
    $list->add('b');
    $list->add('c');
    $list->add('d');

    $subList = $list->subList(1, 3);

    $it = $subList->listIterator();
    $it->next();
    $it->next();
    $it->add('x');

    $this->assertSame(['b', 'c', 'x'], $subList->toArray());
    $this->assertSame(['a', 'b', 'c', 'x', 'd'], $list->toArray());
  }

  public function testListIteratorWithIndex(): void {
    $list = new ArrayList();
    $list->add('a');
    $list->add('b');
    $list->add('c');
    $list->add('d');
    $list->add('e');

    $subList = $list->subList(1, 4);

    $it = $subList->listIterator(1);

    $this->assertTrue($it->hasNext());
    $this->assertTrue($it->hasPrevious());
    $this->assertSame('c', $it->next());
  }

  public function testListIteratorWithIndexOutOfBounds(): void {
    $list = new ArrayList();
    $subList = $list->subList(0, 0);

    $this->expectException(IndexOutOfBoundsException::class);
    $subList->listIterator(100);
  }
}
