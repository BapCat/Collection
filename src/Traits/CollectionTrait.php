<?php namespace BapCat\Collection\Traits;

/**
 * Defines a readable, writable, iterable collection
 */
trait CollectionTrait {
  use ReadableCollectionTrait, WritableCollectionTrait, IterableCollectionTrait;
}
