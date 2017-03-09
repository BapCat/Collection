<?php namespace BapCat\Collection\Interfaces;

/**
 * Defines an immutable collection (although the implementation may be mutable)
 */
interface Collection {
  function __new(array $array);
  
  /**
   * Check if the collection contains a key
   * 
   * @param  mixed  $key The key
   * 
   * @return  bool  True if the key exists, false otherwise
   */
  public function has($key);
  
  /**
   * Search for a value and return its key
   * 
   * @throws  NoSuchValueException  If `$value` wasn't found
   * 
   * @param  mixed  $value
   * 
   * @return  int|string  The key
   */
  public function search($value);
  
  /**
   * Get a value from the collection
   * 
   * @throws  NoSuchKeyException If `$key` refers to a value that does not exist
   * 
   * @param  mixed  $key      The key for which to get a value
   * @param  mixed  $default  (default: null) A default value to return if the collection does not
   *                          contain `$key`.  Prevents NoSuchKeyException from being thrown.
   * 
   * @return  mixed  The value that was bound to `$key`
   */
  public function get($key, $default = null);
  
  /**
   * Get the first value from the collection
   * 
   * @throws  NoSuchKeyException  If the collection is empty
   * 
   * @return  mixed  The first value from the collection
   */
  public function first();
  
  /**
   * Get the first key and value from the collection
   * 
   * @throws  NoSuchKeyException  If the collection is empty
   * 
   * @return  array  The first key and value from the collection
   */
  public function firstPair();
  
  /**
   * Get the last value from the collection
   * 
   * @throws  NoSuchKeyException  If the collection is empty
   * 
   * @return  mixed  The last value from the collection
   */
  public function last();
  
  /**
   * Get the last key and value from the collection
   * 
   * @throws  NoSuchKeyException  If the collection is empty
   * 
   * @return  mixed  The last key and value from the collection
   */
  public function lastPair();
  
  /**
   * Get all keys and values from the collection
   * 
   * @deprecated  Use toArray() instead - it's more descriptive of what it does
   * 
   * @return  array  An associative array of all keys and values contained in the collection
   */
  public function all();
  
  /**
   * Returns the contents of this collection as an array
   * 
   * @return  array  An associative array of all keys and values contained in the collection
   */
  public function toArray();
  
  /**
   * Iterates over each item in the collection and executes the
   * callback, passing the key and value
   * 
   * @param  callable  $callback  `function(int|string $key, mixed $value) : void`
   *                              A callback to be called with the key and value of each item in the collection
   * 
   * @return  void
   */
  public function each(callable $callback);
  
  /**
   * Get all keys from the collection
   * 
   * @return  Collection  All keys contained in this collection
   */
  public function keys();
  
  /**
   * Get all values from the collection
   * 
   * @return  Collection  All values contained in this collection
   */
  public function values();
  
  /**
   * Get the size of the collection
   * 
   * @return  int  The size of the collection
   */
  public function size();
  
  /**
   * True if the collection is empty
   * 
   * @return  bool
   */
  public function isEmpty();
  
  /**
   * Merges two collections together.  If the arrays contain `string` keys, the values from `$other`
   * will take precedence during key collisions.
   * 
   * @param  Collection  $other  The collection to merge with this one
   * 
   * @return  Collection  A new collection containing this collection with `$other` merged with it
   */
  public function merge(Collection $other);
  
  /**
   * Returns a new collection with only the distinct values
   * 
   * @return  Collection  A new collection containing only the distinct values from this collection
   */
  public function distinct();
  
  /**
   * Returns a new collection with the keys and values from this collection reversed
   * 
   * @return  Collection  A new collection containing the keys and values from this collection,
   *                      reversed (`[a => b, c => d]` to `[c => d, a => b]`)
   */
  public function reverse();
  
  /**
   * Returns a new collection with the keys and values from this collection swapped
   * 
   * @return  Collection  A new collection containing the keys and values from this collection,
   *                      swapped (`[a => b, c => d]` to `[b => a, d => c]`)
   */
  public function flip();
  
  /**
   * Extracts a contiguous section of this collection into a new collection
   * 
   * @param  int   $offset  The start of the slice
   * @param  ?int  $length  The length of the slice
   * 
   * @return  Collection  A new collection containing a contiguous subset of this collection
   */
  public function slice($offset, $length = null);
  
  /**
   * Removes a contiguous section of this collection and optionally replaces it with the
   * content of `$replacement`
   * 
   * @param  int          $offset       The start of the splice
   * @param  int          $length       The length of the splice
   * @param  ?Collection  $replacement  (default: null) What to replace the spliced section with
   * 
   * @return  Collection  A new collection containing a contiguous subset of this collection
   */
  public function splice($offset, $length, Collection $replacement = null);
  
  /**
   * Filters a collection based on a callback
   * 
   * @param  callable  $callback  `function(int|string $key, mixed $value) : bool`
   *                              A callback to be called with the key and value of each item
   *                              in the collection. It should return `true` to include the
   *                              key and value in the new collection, and false otherwise.
   * 
   * @return  Collection  A new collection containing a subset of the values of this collection
   */
  public function filter(callable $callback);
  
  /**
   * Filters a collection based on its keys
   * 
   * @param  callable  $callback  `function(int|string $key): bool`
   *                              A callback to be called with the key of each item in the collection.
   *                              It should return `true` to include the key and value in the new
   *                              collection, and false otherwise.
   * 
   * @return  Collection  A new collection containing a subset of the values of this collection
   */
  public function filterByKeys(callable $callback);
  
  /**
   * Filters a collection based on its values
   * 
   * @param  callable  $callback  `function(mixed $value): bool`
   *                              A callback to be called with the value of each item in the collection.
   *                              It should return `true` to include the key and value in the new
   *                              collection, and false otherwise.
   * 
   * @return  Collection  A new collection containing a subset of the values of this collection
   */
  public function filterByValues(callable $callback);
  
  /**
   * Creates a new collection based on the values of this collection with a callback applied to them
   * 
   * @note  Mapping keys is not possible with this method
   * 
   * @param  callable  $callback  `function(int|string $key, mixed $value) : mixed`
   *                              A callback to be called with the key and value of each item
   *                              in the collection.  It should return the modified values.
   * 
   * @return  Collection  A new collection containing a modified values.  Keys are maintained.
   */
  public function map(callable $callback);
  
  /**
   * Creates a new collection with the values of this collection as the values, and the values
   * (<b>not</b> the keys) of `$keys` as the keys.
   * 
   * @param  Collection  $keys  The keys for the new collection
   * 
   * @return  Collection
   */
  public function combine(Collection $keys);
  
  /**
   * Concatenate all elements of the collection together, using `$glue` as a delimeter
   * 
   * @param  string  $glue  (default: empty string)
   * 
   * @return  string
   */
  public function join($glue = '');
}
