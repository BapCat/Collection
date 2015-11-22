[![Build Status](https://travis-ci.org/BapCat/Collection.svg?branch=master)](https://travis-ci.org/BapCat/Collection)
[![Coverage Status](https://coveralls.io/repos/BapCat/Collection/badge.svg?branch=master&service=github)](https://coveralls.io/github/BapCat/Collection?branch=master)
[![License](https://img.shields.io/packagist/l/BapCat/Collection.svg)](https://img.shields.io/packagist/l/BapCat/Collection.svg)

# Collection
A modular, trait-based collection library that puts control of your data back in your hands.

## Installation

### Composer
[Composer](https://getcomposer.org/) is the recommended method of installation for Collection.

```
$ composer require bapcat/collection
```

### GitHub

This package may also be downloaded from [GitHub](https://github.com/BapCat/Collection/).

## Features

### Basic Collection Use
Collections make it easy to control access to your data in an object-oriented way.  The standard implementation supports
reading and writing, as well as standard `foreach` iteration.

```php
$collection = new Collection(['k1' => 'v1']);

var_dump($collection->get('k1')); // 'v1'
var_dump($collection->size()); // 1

$collection->set('k2', 'v2');

$collection->add('v3');

// $collection == ['k1' => 'v1', 'k2' => 'v2', 'v3']

$collection->remove('k2');

// $collection == ['k1' => 'v1', 'v3']

foreach($collection as $k => $v) {
    //
}
```

### Custom Collections
Collections are built with traits, so it's easy to pick-and-choose the features you want.  For example, you may want a
collection that can't be modified from the outside:

```php
class MyCollection implements ReadableCollectionInterface {
    use ReadableCollectionTrait;
    
    protected $collection = [];
}
```

If you would like to implement a full read/write collection, you may extend the basic `Collection` class:

```php
class MyCollection extends Collection {
    //
}
```

Or, you may implement the interfaces and trait:

```php
class MyCollection implements ReadableCollectionInterface, WritableCollectionInterface {
    use ReadableCollectionTrait, WritableCollectionTrait;
}
```

### Array Access
If you would like to use array access with your collection, use the `ArrayAccessCollection` class or the `ArrayAccessCollectionTrait` trait.

### Lazy Loading
Writable collections have support for lazy-loading built in.  Lazy loading can be very useful, for example, in database interactions.

```php
$collection->lazy(1, function($key) {
    return User::find($key);
});
```
