<?php declare(strict_types=1); namespace BapCat\Collection\Maps;

use BapCat\Collection\Functions\BiConsumer;
use BapCat\Collection\Functions\BiFunction;
use BapCat\Collection\Functions\Func;
use BapCat\Collection\IllegalArgumentException;
use BapCat\Collection\NullPointerException;
use BapCat\Collection\Sets\Set;
use BapCat\Collection\UnsupportedOperationException;

/**
 * Contains default implementations for several {@link Map} methods
 */
trait MapDefaultMethods {
  /**
   * Returns the value to which the specified key is mapped, or
   * {@code defaultValue} if this map contains no mapping for the key.
   *
   * @implSpec
   * The default implementation makes no guarantees about synchronization
   * or atomicity properties of this method. Any implementation providing
   * atomicity guarantees must override this method and document its
   * concurrency properties.
   *
   * @param  mixed  $key           The key whose associated value is to be returned
   * @param  mixed  $defaultValue  The default mapping of the key
   *
   * @return  mixed  The value to which the specified key is mapped, or
   * {@code defaultValue} if this map contains no mapping for the key
   *
   * @throws NullPointerException if the specified key is null and this map
   * does not permit null keys
   *
   * @since 1.8
   */
  public function getOrDefault($key, $defaultValue) {
    $v = $this->get($key);

    return ($v !== null || $this->containsKey($key))
      ? $v
      : $defaultValue;
  }

  /**
   * Performs the given action for each entry in this map until all entries
   * have been processed or the action throws an exception.   Unless
   * otherwise specified by the implementing class, actions are performed in
   * the order of entry set iteration (if an iteration order is specified.)
   * Exceptions thrown by the action are relayed to the caller.
   *
   * @implSpec
   * The default implementation is equivalent to, for this {@code map}:
   *
   *     for (Map.Entry<K, V> entry : map.entrySet()) {
   *         action.accept(entry.getKey(), entry.getValue());
   *     }
   *
   * The default implementation makes no guarantees about synchronization
   * or atomicity properties of this method. Any implementation providing
   * atomicity guarantees must override this method and document its
   * concurrency properties.
   *
   * @param  BiConsumer  $action  The action to be performed for each entry
   *
   * @return  void
   *
   * @throws NullPointerException if the specified action is null
   *
   * @since 1.8
   */
  public function each(BiConsumer $action): void {
    foreach($this->entrySet() as $entry) {
      /**
       * @var  Entry  $entry
       */

      $action->accept($entry->getKey(), $entry->getValue());
    }
  }

  /**
   * Replaces each entry's value with the result of invoking the given
   * function on that entry until all entries have been processed or the
   * function throws an exception.  Exceptions thrown by the function are
   * relayed to the caller.
   *
   * @implSpec
   * <p>The default implementation is equivalent to, for this {@code map}:
   *
   *     for (Map.Entry<K, V> entry : map.entrySet()) {
   *         entry.setValue(function.apply(entry.getKey(), entry.getValue()));
   *     }
   *
   * <p>The default implementation makes no guarantees about synchronization
   * or atomicity properties of this method. Any implementation providing
   * atomicity guarantees must override this method and document its
   * concurrency properties.
   *
   * @param  BiFunction  $func  The function to apply to each entry
   *
   * @return  void
   *
   * @throws UnsupportedOperationException if the {@code set} operation
   * is not supported by this map's entry set iterator.
   * @throws NullPointerException if the specified function is null, or the
   * specified replacement value is null, and this map does not permit null
   * values
   * @throws NullPointerException if function or a replacement value is null,
   *         and this map does not permit null keys or values
   * @throws IllegalArgumentException if some property of a replacement value
   *         prevents it from being stored in this map
   *         (<a href="{@docRoot}/java/util/Collection.html#optional-restrictions">optional</a>)
   * @since 1.8
   */
  public function replaceAll(BiFunction $func): void {
    foreach($this->entrySet() as $entry) {
      /**
       * @var  Entry  $entry
       */

      $entry->setValue($func->apply($entry->getKey(), $entry->getValue()));
    }
  }

  /**
   * If the specified key is not already associated with a value (or is mapped
   * to {@code null}) associates it with the given value and returns
   * {@code null}, else returns the current value.
   *
   * @implSpec
   * The default implementation is equivalent to, for this {@code
   * map}:
   *
   *     V v = map.get(key);
   *     if (v == null) {
   *         v = map.put(key, value);
   *     }
   *
   *     return v;
   *
   * <p>The default implementation makes no guarantees about synchronization
   * or atomicity properties of this method. Any implementation providing
   * atomicity guarantees must override this method and document its
   * concurrency properties.
   *
   * @param  mixed  $key    The key with which the specified value is to be associated
   * @param  mixed  $value  The value to be associated with the specified key
   *
   * @return  mixed  The previous value associated with the specified key, or
   *         {@code null} if there was no mapping for the key.
   *         (A {@code null} return can also indicate that the map
   *         previously associated {@code null} with the key,
   *         if the implementation supports null values.)
   *
   * @throws UnsupportedOperationException if the {@code put} operation
   *         is not supported by this map
   * @throws NullPointerException if the specified key or value is null,
   *         and this map does not permit null keys or values
   * @throws IllegalArgumentException if some property of the specified key
   *         or value prevents it from being stored in this map
   *
   * @since 1.8
   */
  public function putIfAbsent($key, $value) {
    return $this->get($key) ?: $this->put($key, $value);
  }

  /**
   * Replaces the entry for the specified key only if it is
   * currently mapped to some value.
   *
   * @implSpec
   * The default implementation is equivalent to, for this {@code map}:
   *
   * <pre> {@code
   * if (map.containsKey(key)) {
   *     return map.put(key, value);
   * } else
   *     return null;
   * }</pre>
   *
   * <p>The default implementation makes no guarantees about synchronization
   * or atomicity properties of this method. Any implementation providing
   * atomicity guarantees must override this method and document its
   * concurrency properties.
   *
   * @param  mixed  $key    The key with which the specified value is associated
   * @param  mixed  $value  The value to be associated with the specified key
   *
   * @return  mixed  The previous value associated with the specified key, or
   *         {@code null} if there was no mapping for the key.
   *         (A {@code null} return can also indicate that the map
   *         previously associated {@code null} with the key,
   *         if the implementation supports null values.)
   * @throws UnsupportedOperationException if the {@code put} operation
   *         is not supported by this map
   * @throws NullPointerException if the specified key or value is null,
   *         and this map does not permit null keys or values
   * @throws IllegalArgumentException if some property of the specified key
   *         or value prevents it from being stored in this map
   *
   * @since 1.8
   */
  public function replace($key, $value) {
    $curValue = $this->get($key);

    if($curValue !== null || $this->containsKey($key)) {
      $curValue = $this->put($key, $value);
    }

    return $curValue;
  }

  /**
   * If the specified key is not already associated with a value (or is mapped
   * to {@code null}), attempts to compute its value using the given mapping
   * function and enters it into this map unless {@code null}.
   *
   * <p>If the function returns {@code null} no mapping is recorded. If
   * the function itself throws an (unchecked) exception, the
   * exception is rethrown, and no mapping is recorded.  The most
   * common usage is to construct a new object serving as an initial
   * mapped value or memoized result, as in:
   *
   *     map.computeIfAbsent(key, k -> new Value(f(k)));
   *
   * <p>Or to implement a multi-value map, {@code Map<K,Collection<V>>},
   * supporting multiple values per key:
   *
   *     map.computeIfAbsent(key, k -> new HashSet<V>()).add(v);
   *
   *
   * @implSpec
   * The default implementation is equivalent to the following steps for this
   * {@code map}, then returning the current value or {@code null} if now
   * absent:
   *
   *     if (map.get(key) == null) {
   *         V newValue = mappingFunction.apply(key);
   *         if (newValue != null) {
   *             map.put(key, newValue);
   *         }
   *     }
   *
   * <p>The default implementation makes no guarantees about synchronization
   * or atomicity properties of this method. Any implementation providing
   * atomicity guarantees must override this method and document its
   * concurrency properties. In particular, all implementations of
   * subinterface {@link java.util.concurrent.ConcurrentMap} must document
   * whether the function is applied once atomically only if the value is not
   * present.
   *
   * @param  mixed  $key              The key with which the specified value is to be associated
   * @param  Func   $mappingFunction  The function to compute a value
   *
   * @return  mixed  The current (existing or computed) value associated with
   *                 the specified key, or null if the computed value is null
   *
   * @throws NullPointerException if the specified key is null and
   *         this map does not support null keys, or the mappingFunction
   *         is null
   * @throws UnsupportedOperationException if the {@code put} operation
   *         is not supported by this map
   *
   * @since 1.8
   */
  public function computeIfAbsent($key, Func $mappingFunction) {
    $v = $this->get($key);

    if($v === null) {
      $newValue = $mappingFunction->apply($key);

      if($newValue !== null) {
        $this->put($key, $newValue);
        return $newValue;
      }
    }

    return $v;
  }

  /**
   * If the value for the specified key is present and non-null, attempts to
   * compute a new mapping given the key and its current mapped value.
   *
   * <p>If the function returns {@code null}, the mapping is removed.  If the
   * function itself throws an (unchecked) exception, the exception is
   * rethrown, and the current mapping is left unchanged.
   *
   * @implSpec
   * The default implementation is equivalent to performing the following
   * steps for this {@code map}, then returning the current value or
   * {@code null} if now absent:
   *
   *     if (map.get(key) != null) {
   *         V oldValue = map.get(key);
   *         V newValue = remappingFunction.apply(key, oldValue);
   *         if (newValue != null) {
   *             map.put(key, newValue);
   *         } else {
   *             map.remove(key);
   *         }
   *     }
   *
   * <p>The default implementation makes no guarantees about synchronization
   * or atomicity properties of this method. Any implementation providing
   * atomicity guarantees must override this method and document its
   * concurrency properties. In particular, all implementations of
   * subinterface {@link java.util.concurrent.ConcurrentMap} must document
   * whether the function is applied once atomically only if the value is not
   * present.
   *
   * @param  mixed       $key                The key with which the specified value is to be associated
   * @param  BiFunction  $remappingFunction  The function to compute a value
   *
   * @return  mixed  The new value associated with the specified key, or null if none
   *
   * @throws NullPointerException if the specified key is null and
   *         this map does not support null keys, or the
   *         remappingFunction is null
   * @throws UnsupportedOperationException if the {@code put} operation
   *         is not supported by this map
   *
   * @since 1.8
   */
  public function computeIfPresent($key, BiFunction $remappingFunction) {
    $oldValue = $this->get($key);

    if($oldValue !== null) {
      $newValue = $remappingFunction->apply($key, $oldValue);

      if($newValue !== null) {
        $this->put($key, $newValue);
        return $newValue;
      } else {
        $this->remove($key);
        return null;
      }
    } else {
      return null;
    }
  }

  /**
   * Attempts to compute a mapping for the specified key and its current
   * mapped value (or {@code null} if there is no current mapping). For
   * example, to either create or append a {@code String} msg to a value
   * mapping:
   *
   *     map.compute(key, (k, v) -> (v == null) ? msg : v.concat(msg))
   *
   * (Method {@link #merge merge()} is often simpler to use for such purposes.)
   *
   * <p>If the function returns {@code null}, the mapping is removed (or
   * remains absent if initially absent).  If the function itself throws an
   * (unchecked) exception, the exception is rethrown, and the current mapping
   * is left unchanged.
   *
   * @implSpec
   * The default implementation is equivalent to performing the following
   * steps for this {@code map}, then returning the current value or
   * {@code null} if absent:
   *
   *     V oldValue = map.get(key);
   *     V newValue = remappingFunction.apply(key, oldValue);
   *     if (oldValue != null ) {
   *         if (newValue != null) {
   *             map.put(key, newValue);
   *         } else {
   *             map.remove(key);
   *         }
   *     } else {
   *         if (newValue != null) {
   *             map.put(key, newValue);
   *         } else {
   *             return null;
   *         }
   *     }
   *
   * <p>The default implementation makes no guarantees about synchronization
   * or atomicity properties of this method. Any implementation providing
   * atomicity guarantees must override this method and document its
   * concurrency properties. In particular, all implementations of
   * subinterface {@link java.util.concurrent.ConcurrentMap} must document
   * whether the function is applied once atomically only if the value is not
   * present.
   *
   * @param  mixed       $key                The key with which the specified value is to be associated
   * @param  BiFunction  $remappingFunction  The function to compute a value
   *
   * @return  mixed  the new value associated with the specified key, or null if none
   *
   * @throws NullPointerException if the specified key is null and
   *         this map does not support null keys, or the
   *         remappingFunction is null
   * @throws UnsupportedOperationException if the {@code put} operation
   *         is not supported by this map
   *
   * @since 1.8
   */
  public function compute($key, BiFunction $remappingFunction) {
    $oldValue = $this->get($key);
    $newValue = $remappingFunction->apply($key, $oldValue);

    if($newValue === null) {
      // delete mapping
      if($oldValue !== null || $this->containsKey($key)) {
        // something to remove
        $this->remove($key);
        return null;
      } else {
        // nothing to do. Leave things as they were.
        return null;
      }
    } else {
      // add or replace old mapping
      $this->put($key, $newValue);
      return $newValue;
    }
  }

  /**
   * If the specified key is not already associated with a value or is
   * associated with null, associates it with the given non-null value.
   * Otherwise, replaces the associated value with the results of the given
   * remapping function, or removes if the result is {@code null}. This
   * method may be of use when combining multiple mapped values for a key.
   * For example, to either create or append a {@code String msg} to a
   * value mapping:
   *
   * <pre> {@code
   * map.merge(key, msg, String::concat)
   * }</pre>
   *
   * <p>If the function returns {@code null} the mapping is removed.  If the
   * function itself throws an (unchecked) exception, the exception is
   * rethrown, and the current mapping is left unchanged.
   *
   * @implSpec
   * The default implementation is equivalent to performing the following
   * steps for this {@code map}, then returning the current value or
   * {@code null} if absent:
   *
   *    V oldValue = map.get(key);
   *    V newValue = (oldValue == null) ? value :
   *        remappingFunction.apply(oldValue, value);
   *     if (newValue == null) {
   *         map.remove(key);
   *     } else {
   *         map.put(key, newValue);
   *     }
   *
   * <p>The default implementation makes no guarantees about synchronization
   * or atomicity properties of this method. Any implementation providing
   * atomicity guarantees must override this method and document its
   * concurrency properties. In particular, all implementations of
   * subinterface {@link java.util.concurrent.ConcurrentMap} must document
   * whether the function is applied once atomically only if the value is not
   * present.
   *
   * @param  mixed       $key    The key with which the resulting value is to be associated
   * @param  mixed       $value  The non-null value to be merged with the existing value
   *                             associated with the key or, if no existing value or a null value
   *                             is associated with the key, to be associated with the key
   * @param  BiFunction  $remappingFunction  The function to recompute a value if present
   *
   * @return  mixed  The new value associated with the specified key, or null if no
   *                 value is associated with the key
   *
   * @throws UnsupportedOperationException if the {@code put} operation
   *         is not supported by this map
   * @throws NullPointerException if the specified key is null and this map
   *         does not support null keys or the value or remappingFunction is
   *         null
   *
   * @since 1.8
   */
  public function merge($key, $value, BiFunction $remappingFunction) {
    $oldValue = $this->get($key);
    $newValue = ($oldValue === null) ? $value :
      $remappingFunction->apply($oldValue, $value);

    if($newValue === null) {
      $this->remove($key);
    } else {
      $this->put($key, $newValue);
    }

    return $newValue;
  }

  /**
   * @param  mixed  $key
   *
   * @return  mixed
   */
  public abstract function get($key);

  /**
   * @param  mixed  $key
   *
   * @return  bool
   */
  public abstract function containsKey($key): bool;

  /**
   * @return  Set
   */
  public abstract function entrySet(): Set;

  /**
   * @param  mixed  $key
   * @param  mixed  $value
   *
   * @return  mixed
   */
  public abstract function put($key, $value);

  /**
   * @param  mixed  $key
   */
  public abstract function remove($key);
}
