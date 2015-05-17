<?php namespace LordMonoxide\Collection;

use IteratorAggregate;

class Collection implements ReadableCollectionInterface, WritableCollectionInterface, IteratorAggregate {
  use ReadableCollectionTrait, WritableCollectionTrait, IterableCollectionTrait;
}
