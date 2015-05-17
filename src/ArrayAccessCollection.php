<?php namespace LordMonoxide\Collection;

use LordMonoxide\Collection\Interfaces\ReadableCollectionInterface;
use LordMonoxide\Collection\Interfaces\WritableCollectionInterface;
use LordMonoxide\Collection\Traits\ArrayAccessCollectionTrait;
use LordMonoxide\Collection\Traits\CollectionTrait;

use ArrayAccess;
use IteratorAggregate;

class ArrayAccessCollection implements ReadableCollectionInterface, WritableCollectionInterface, IteratorAggregate, ArrayAccess {
  use CollectionTrait, ArrayAccessCollectionTrait;
}
