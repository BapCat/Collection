<?php namespace LordMonoxide\Collection;

use LordMonoxide\Collection\Interfaces\ReadableCollectionInterface;
use LordMonoxide\Collection\Interfaces\WritableCollectionInterface;
use LordMonoxide\Collection\Traits\CollectionTrait;

use IteratorAggregate;

class Collection implements ReadableCollectionInterface, WritableCollectionInterface, IteratorAggregate {
  use CollectionTrait;
}
