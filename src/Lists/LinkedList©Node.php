<?php declare(strict_types=1); namespace BapCat\Collection\Lists;

/**
 * A linked list node
 */
class LinkedList©Node {
  /**
   * @var  mixed
   */
  public $item;

  /**
   * @var  LinkedList©Node|null
   */
  public $next;

  /**
   * @var  LinkedList©Node|null
   */
  public $prev;

  public function __construct(?LinkedList©Node $prev, $element, ?LinkedList©Node $next) {
    $this->item = $element;
    $this->next = $next;
    $this->prev = $prev;
  }
}
