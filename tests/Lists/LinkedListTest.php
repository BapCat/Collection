<?php declare(strict_types=1);

use BapCat\Collection\IndexOutOfBoundsException;
use BapCat\Collection\Lists\ArrayList;
use BapCat\Collection\Lists\LinkedList;
use BapCat\Collection\NoSuchElementException;
use PHPUnit\Framework\TestCase;

class LinkedListTest extends TestCase {
  public function testPush(): void {
    $list = new LinkedList();

    $list->push('a');
    $list->push('b');
    $list->push('c');

    $this->assertSame(3, $list->size());
    $this->assertSame(['c', 'b', 'a'], $list->toArray());
  }

  public function testPop(): void {
    $list = new LinkedList();

    $list->push('a');
    $list->push('b');
    $list->push('c');

    $this->assertSame('c', $list->pop());
    $this->assertSame(2, $list->size());
    $this->assertSame(['b', 'a'], $list->toArray());

    $this->assertSame('b', $list->pop());
    $this->assertSame(1, $list->size());
    $this->assertSame(['a'], $list->toArray());

    $this->assertSame('a', $list->pop());
    $this->assertSame(0, $list->size());
    $this->assertSame([], $list->toArray());
  }

  public function testPopWhileEmpty(): void {
    $list = new LinkedList();

    $this->expectException(NoSuchElementException::class);
    $list->pop();
  }

  public function testGetFirst(): void {
    $list = new LinkedList();

    $list->push('a');
    $list->push('b');
    $list->push('c');

    $this->assertSame(3, $list->size());
    $this->assertSame('c', $list->getFirst());
    $this->assertSame(3, $list->size());
  }

  public function testGetFirstWhileEmpty(): void {
    $list = new LinkedList();

    $this->expectException(NoSuchElementException::class);
    $list->getFirst();
  }

  public function testGetLast(): void {
    $list = new LinkedList();

    $list->push('a');
    $list->push('b');
    $list->push('c');

    $this->assertSame(3, $list->size());
    $this->assertSame('a', $list->getLast());
    $this->assertSame(3, $list->size());
  }

  public function testGetLastWhileEmpty(): void {
    $list = new LinkedList();

    $this->expectException(NoSuchElementException::class);
    $list->getLast();
  }

  public function testPeek(): void {
    $list = new LinkedList();

    $list->push('a');
    $list->push('b');
    $list->push('c');

    $this->assertSame(3, $list->size());
    $this->assertSame('c', $list->peek());
    $this->assertSame(3, $list->size());
  }

  public function testPeekFirst(): void {
    $list = new LinkedList();

    $list->push('a');
    $list->push('b');
    $list->push('c');

    $this->assertSame(3, $list->size());
    $this->assertSame('c', $list->peekFirst());
    $this->assertSame(3, $list->size());
  }

  public function testPeekLast(): void {
    $list = new LinkedList();

    $list->push('a');
    $list->push('b');
    $list->push('c');

    $this->assertSame(3, $list->size());
    $this->assertSame('a', $list->peekLast());
    $this->assertSame(3, $list->size());
  }

  public function testPoll(): void {
    $list = new LinkedList();

    $list->push('a');
    $list->push('b');
    $list->push('c');

    $this->assertSame(3, $list->size());
    $this->assertSame('c', $list->poll());

    $this->assertSame(2, $list->size());
    $this->assertSame('b', $list->poll());

    $this->assertSame(1, $list->size());
    $this->assertSame('a', $list->poll());

    $this->assertSame(0, $list->size());
    $this->assertSame(null, $list->poll());

    $this->assertSame(0, $list->size());
  }

  public function testPollFirst(): void {
    $list = new LinkedList();

    $list->push('a');
    $list->push('b');
    $list->push('c');

    $this->assertSame(3, $list->size());
    $this->assertSame('c', $list->pollFirst());

    $this->assertSame(2, $list->size());
    $this->assertSame('b', $list->pollFirst());

    $this->assertSame(1, $list->size());
    $this->assertSame('a', $list->pollFirst());

    $this->assertSame(0, $list->size());
    $this->assertSame(null, $list->pollFirst());

    $this->assertSame(0, $list->size());
  }

  public function testPollLast(): void {
    $list = new LinkedList();

    $list->push('a');
    $list->push('b');
    $list->push('c');

    $this->assertSame(3, $list->size());
    $this->assertSame('a', $list->pollLast());

    $this->assertSame(2, $list->size());
    $this->assertSame('b', $list->pollLast());

    $this->assertSame(1, $list->size());
    $this->assertSame('c', $list->pollLast());

    $this->assertSame(0, $list->size());
    $this->assertSame(null, $list->pollLast());

    $this->assertSame(0, $list->size());
  }

  public function testElement(): void {
    $list = new LinkedList();

    $list->push('a');
    $list->push('b');
    $list->push('c');

    $this->assertSame(3, $list->size());
    $this->assertSame('c', $list->element());
    $this->assertSame(3, $list->size());
  }

  public function testRemoveFirst(): void {
    $list = new LinkedList();

    $list->push('a');
    $list->push('b');
    $list->push('c');

    $this->assertSame('c', $list->removeFirst());
    $this->assertSame(2, $list->size());
    $this->assertSame(['b', 'a'], $list->toArray());
  }

  public function testRemoveFirstWhileEmpty(): void {
    $list = new LinkedList();

    $this->expectException(NoSuchElementException::class);
    $list->removeFirst();
  }

  public function testRemoveLast(): void {
    $list = new LinkedList();

    $list->push('a');
    $list->push('b');
    $list->push('c');

    $this->assertSame('a', $list->removeLast());
    $this->assertSame(2, $list->size());
    $this->assertSame(['c', 'b'], $list->toArray());
  }

  public function testRemoveLastWhileEmpty(): void {
    $list = new LinkedList();

    $this->expectException(NoSuchElementException::class);
    $list->removeLast();
  }

  public function testAddFirst(): void {
    $list = new LinkedList();

    $list->addFirst('a');
    $list->addFirst('b');
    $list->addFirst('c');

    $this->assertSame(3, $list->size());
    $this->assertSame(['c', 'b', 'a'], $list->toArray());
  }

  public function testAddLast(): void {
    $list = new LinkedList();

    $list->addLast('a');
    $list->addLast('b');
    $list->addLast('c');

    $this->assertSame(3, $list->size());
    $this->assertSame(['a', 'b', 'c'], $list->toArray());
  }

  public function testIndexOf(): void {
    $list = new LinkedList();

    $list->push('a');
    $list->push('b');
    $list->push('c');
    $list->push('d');
    $list->push('a');

    $this->assertSame(0, $list->indexOf('a'));
    $this->assertSame(3, $list->indexOf('b'));
    $this->assertSame(2, $list->indexOf('c'));
  }

  public function testIndexOfDoesntExist(): void {
    $list = new LinkedList();

    $list->push('a');
    $list->push('b');
    $list->push('c');

    $this->assertSame(-1, $list->indexOf('x'));
  }

  public function testIndexOfOnEmpty(): void {
    $list = new LinkedList();

    $this->assertSame(-1, $list->indexOf('x'));
  }

  public function testLastIndexOf(): void {
    $list = new LinkedList();

    $list->push('a');
    $list->push('b');
    $list->push('c');
    $list->push('d');
    $list->push('a');

    $this->assertSame(4, $list->lastIndexOf('a'));
    $this->assertSame(3, $list->lastIndexOf('b'));
    $this->assertSame(2, $list->lastIndexOf('c'));
  }

  public function testLastIndexOfDoesntExist(): void {
    $list = new LinkedList();

    $list->push('a');
    $list->push('b');
    $list->push('c');

    $this->assertSame(-1, $list->lastIndexOf('x'));
  }

  public function testLastIndexOfOnEmpty(): void {
    $list = new LinkedList();

    $this->assertSame(-1, $list->lastIndexOf('x'));
  }

  public function testContains(): void {
    $list = new LinkedList();

    $list->push('a');
    $list->push('b');
    $list->push('c');

    $this->assertTrue($list->contains('a'));
    $this->assertFalse($list->contains('x'));
  }

  public function testContainsOnEmpty(): void {
    $list = new LinkedList();

    $this->assertFalse($list->contains('x'));
  }

  public function testOffer(): void {
    $list = new LinkedList();

    $list->offer('a');
    $list->offer('b');
    $list->offer('c');

    $this->assertSame(3, $list->size());
    $this->assertSame(['a', 'b', 'c'], $list->toArray());
  }

  public function testOfferFirst(): void {
    $list = new LinkedList();

    $list->offerFirst('a');
    $list->offerFirst('b');
    $list->offerFirst('c');

    $this->assertSame(3, $list->size());
    $this->assertSame(['c', 'b', 'a'], $list->toArray());
  }

  public function testOfferLast(): void {
    $list = new LinkedList();

    $list->offerLast('a');
    $list->offerLast('b');
    $list->offerLast('c');

    $this->assertSame(3, $list->size());
    $this->assertSame(['a', 'b', 'c'], $list->toArray());
  }

  public function testAdd(): void {
    $list = new LinkedList();

    $list->add('a');
    $list->add('b');
    $list->add('c');

    $this->assertSame(3, $list->size());
    $this->assertSame(['a', 'b', 'c'], $list->toArray());
  }

  public function testAddAt(): void {
    $list = new LinkedList();

    $list->add('a');
    $list->add('b');
    $list->add('c');

    $list->addAt(0, '1');
    $this->assertSame(4, $list->size());
    $this->assertSame(['1', 'a', 'b', 'c'], $list->toArray());

    $list->addAt(2, '2');
    $this->assertSame(5, $list->size());
    $this->assertSame(['1', 'a', '2', 'b', 'c'], $list->toArray());

    $list->addAt(5, '3');
    $this->assertSame(6, $list->size());
    $this->assertSame(['1', 'a', '2', 'b', 'c', '3'], $list->toArray());
  }

  public function testAddAtNegative(): void {
    $list = new LinkedList();

    $list->add('a');
    $list->add('b');
    $list->add('c');

    $this->expectException(IndexOutOfBoundsException::class);
    $list->addAt(-1, '1');
  }

  public function testAddAtOob(): void {
    $list = new LinkedList();

    $list->add('a');
    $list->add('b');
    $list->add('c');

    $this->expectException(IndexOutOfBoundsException::class);
    $list->addAt(100, '1');
  }

  public function testAddAtEmpty(): void {
    $list = new LinkedList();

    $list->addAt(0, '1');

    $this->assertSame('1', $list->get(0));
  }

  public function testAddAll() {
    $list = new LinkedList();
    $list->add('a');
    $list->add('e');

    $list2 = new ArrayList();
    $list2->add('b');
    $list2->add('c');
    $list2->add('d');

    $this->assertTrue($list->addAll($list2));

    $this->assertSame(5, $list->size());
    $this->assertSame(['a', 'e', 'b', 'c', 'd'], $list->toArray());
  }

  public function testAddEmpty() {
    $list = new LinkedList();

    $list2 = new ArrayList();
    $list2->add('b');
    $list2->add('c');
    $list2->add('d');

    $this->assertTrue($list->addAll($list2));

    $this->assertSame(3, $list->size());
    $this->assertSame(['b', 'c', 'd'], $list->toArray());
  }

  public function testAddAllEmptyCollection() {
    $list = new LinkedList();
    $list->add('a');
    $list->add('e');

    $list2 = new ArrayList();

    $this->assertFalse($list->addAll($list2));

    $this->assertSame(2, $list->size());
    $this->assertSame(['a', 'e'], $list->toArray());
  }

  public function testAddAllAt() {
    $list = new LinkedList();
    $list->add('a');
    $list->add('e');

    $list2 = new ArrayList();
    $list2->add('b');
    $list2->add('c');
    $list2->add('d');

    $this->assertTrue($list->addAllAt(1, $list2));

    $this->assertSame(5, $list->size());
    $this->assertSame(['a', 'b', 'c', 'd', 'e'], $list->toArray());
  }

  public function testAddAllAtNegative() {
    $list = new LinkedList();
    $list->add('a');
    $list->add('e');

    $list2 = new ArrayList();
    $list2->add('b');
    $list2->add('c');
    $list2->add('d');

    $this->expectException(IndexOutOfBoundsException::class);
    $list->addAllAt(-1, $list2);
  }

  public function testAddAllAtOob() {
    $list = new LinkedList();
    $list->add('a');
    $list->add('e');

    $list2 = new ArrayList();
    $list2->add('b');
    $list2->add('c');
    $list2->add('d');

    $this->expectException(IndexOutOfBoundsException::class);
    $list->addAllAt(-1, $list2);
  }

  public function testAddAllAtEmptyCollection() {
    $list = new LinkedList();
    $list->add('a');
    $list->add('e');

    $list2 = new ArrayList();

    $this->assertFalse($list->addAllAt(1, $list2));

    $this->assertSame(2, $list->size());
    $this->assertSame(['a', 'e'], $list->toArray());
  }

  public function testConstructWithCollection() {
    $list2 = new ArrayList();
    $list2->add('b');
    $list2->add('c');
    $list2->add('d');

    $list = new LinkedList($list2);

    $this->assertSame(3, $list->size());
    $this->assertSame(['b', 'c', 'd'], $list->toArray());
  }

  public function testRemove(): void {
    $list = new LinkedList();

    $list->add('a');
    $list->add('b');
    $list->add('c');

    $this->assertSame(3, $list->size());
    $this->assertSame(['a', 'b', 'c'], $list->toArray());

    $this->assertTrue($list->remove('b'));

    $this->assertSame(2, $list->size());
    $this->assertSame(['a', 'c'], $list->toArray());
  }

  public function testRemoveDoestExist(): void {
    $list = new LinkedList();

    $list->add('a');
    $list->add('b');
    $list->add('c');

    $this->assertSame(3, $list->size());
    $this->assertSame(['a', 'b', 'c'], $list->toArray());

    $this->assertFalse($list->remove('x'));

    $this->assertSame(3, $list->size());
    $this->assertSame(['a', 'b', 'c'], $list->toArray());
  }

  public function testRemoveEmpty(): void {
    $list = new LinkedList();

    $this->assertFalse($list->remove('x'));

    $this->assertSame(0, $list->size());
    $this->assertSame([], $list->toArray());
  }

  public function testRemoveFirstOccurrence(): void {
    $list = new LinkedList();

    $list->add('a');
    $list->add('b');
    $list->add('c');
    $list->add('b');

    $this->assertSame(4, $list->size());
    $this->assertSame(['a', 'b', 'c', 'b'], $list->toArray());

    $this->assertTrue($list->removeFirstOccurrence('b'));

    $this->assertSame(3, $list->size());
    $this->assertSame(['a', 'c', 'b'], $list->toArray());
  }

  public function testRemoveFirstOccurrenceDoestExist(): void {
    $list = new LinkedList();

    $list->add('a');
    $list->add('b');
    $list->add('c');
    $list->add('b');

    $this->assertSame(4, $list->size());
    $this->assertSame(['a', 'b', 'c', 'b'], $list->toArray());

    $this->assertFalse($list->removeFirstOccurrence('x'));

    $this->assertSame(4, $list->size());
    $this->assertSame(['a', 'b', 'c', 'b'], $list->toArray());
  }

  public function testRemoveFirstOccurrenceEmpty(): void {
    $list = new LinkedList();

    $this->assertFalse($list->remove('x'));

    $this->assertSame(0, $list->size());
    $this->assertSame([], $list->toArray());
  }

  public function testRemoveLastOccurrence(): void {
    $list = new LinkedList();

    $list->add('a');
    $list->add('b');
    $list->add('c');
    $list->add('b');

    $this->assertSame(4, $list->size());
    $this->assertSame(['a', 'b', 'c', 'b'], $list->toArray());

    $this->assertTrue($list->removeLastOccurrence('b'));

    $this->assertSame(3, $list->size());
    $this->assertSame(['a', 'b', 'c'], $list->toArray());
  }

  public function testRemoveLastOccurrenceDoestExist(): void {
    $list = new LinkedList();

    $list->add('a');
    $list->add('b');
    $list->add('c');
    $list->add('b');

    $this->assertSame(4, $list->size());
    $this->assertSame(['a', 'b', 'c', 'b'], $list->toArray());

    $this->assertFalse($list->removeLastOccurrence('x'));

    $this->assertSame(4, $list->size());
    $this->assertSame(['a', 'b', 'c', 'b'], $list->toArray());
  }

  public function testRemoveLastOccurrenceEmpty(): void {
    $list = new LinkedList();

    $this->assertFalse($list->removeLastOccurrence('x'));

    $this->assertSame(0, $list->size());
    $this->assertSame([], $list->toArray());
  }

  public function testRemoveAt(): void {
    $list = new LinkedList();

    $list->add('a');
    $list->add('b');
    $list->add('c');

    $this->assertSame('a', $list->removeAt(0));
    $this->assertSame('c', $list->removeAt(1));

    $this->assertSame(1, $list->size());
    $this->assertSame(['b'], $list->toArray());
  }

  public function testRemoveAtNegative(): void {
    $list = new LinkedList();

    $list->add('a');
    $list->add('b');
    $list->add('c');

    $this->expectException(IndexOutOfBoundsException::class);
    $list->removeAt(-1);
  }

  public function testRemoveAtOob(): void {
    $list = new LinkedList();

    $list->add('a');
    $list->add('b');
    $list->add('c');

    $this->expectException(IndexOutOfBoundsException::class);
    $list->removeAt(100);
  }

  public function testClear(): void {
    $list = new LinkedList();

    $list->push('a');
    $list->push('b');
    $list->push('c');

    $this->assertSame(3, $list->size());
    $this->assertSame(['c', 'b', 'a'], $list->toArray());

    $list->clear();

    $this->assertSame(0, $list->size());
    $this->assertSame([], $list->toArray());
  }

  public function testGet(): void {
    $list = new LinkedList();

    $list->push('a');
    $list->push('b');
    $list->push('c');
    $list->push('d');

    $this->assertSame(4, $list->size());
    $this->assertSame(['d', 'c', 'b', 'a'], $list->toArray());

    $this->assertSame('d', $list->get(0));
    $this->assertSame('c', $list->get(1));
    $this->assertSame('b', $list->get(2));
    $this->assertSame('a', $list->get(3));
  }

  public function testGetNegative(): void {
    $list = new LinkedList();

    $list->push('a');
    $list->push('b');
    $list->push('c');
    $list->push('d');

    $this->expectException(IndexOutOfBoundsException::class);
    $list->get(-1);
  }

  public function testGetOob(): void {
    $list = new LinkedList();

    $list->push('a');
    $list->push('b');
    $list->push('c');
    $list->push('d');

    $this->expectException(IndexOutOfBoundsException::class);
    $list->get(100);
  }

  public function testSet(): void {
    $list = new LinkedList();

    $list->push('a');
    $list->push('b');
    $list->push('c');

    $this->assertSame(3, $list->size());
    $this->assertSame(['c', 'b', 'a'], $list->toArray());

    $this->assertSame('c', $list->set(0, 'cc'));
    $this->assertSame('b', $list->set(1, 'bb'));
    $this->assertSame('a', $list->set(2, 'aa'));

    $this->assertSame('cc', $list->get(0));
    $this->assertSame('bb', $list->get(1));
    $this->assertSame('aa', $list->get(2));

    $this->assertSame(3, $list->size());
    $this->assertSame(['cc', 'bb', 'aa'], $list->toArray());
  }

  public function testSetEmpty(): void {
    $list = new LinkedList();

    $this->expectException(IndexOutOfBoundsException::class);
    $list->set(0, '1');
  }

  public function testSetNegative(): void {
    $list = new LinkedList();

    $list->push('a');
    $list->push('b');
    $list->push('c');

    $this->expectException(IndexOutOfBoundsException::class);
    $list->set(-1, '1');
  }

  public function testSetOob(): void {
    $list = new LinkedList();

    $list->push('a');
    $list->push('b');
    $list->push('c');

    $this->expectException(IndexOutOfBoundsException::class);
    $list->set(100, '1');
  }

  public function testIterator(): void {
    $list = new LinkedList();

    $list->push('a');
    $list->push('b');
    $list->push('c');

    $elements = [];
    for($it = $list->iterator(); $it->hasNext(); ) {
      $elements[] = $it->next();
    }

    $this->assertSame(['c', 'b', 'a'], $elements);
  }

  public function testReverseIterator(): void {
    $list = new LinkedList();

    $list->push('a');
    $list->push('b');
    $list->push('c');

    $elements = [];
    for($it = $list->descendingIterator(); $it->hasNext(); ) {
      $elements[] = $it->next();
    }

    $this->assertSame(['a', 'b', 'c'], $elements);
  }

  public function testForEach(): void {
    $list = new LinkedList();

    $list->push('a');
    $list->push('b');
    $list->push('c');

    $elements = [];
    foreach($list as $element) {
      $elements[] = $element;
    }

    $this->assertSame(['c', 'b', 'a'], $elements);
  }
}
