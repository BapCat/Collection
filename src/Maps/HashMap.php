<?php declare(strict_types=1); namespace BapCat\Collection\Maps;

use BapCat\Collection\Collection;
use BapCat\Collection\Functions\BiConsumer;
use BapCat\Collection\Functions\BiFunction;
use BapCat\Collection\Functions\Func;
use BapCat\Collection\Sets\Set;

/**
* Hash table based implementation of the <tt>Map</tt> interface.  This
* implementation provides all of the optional map operations, and permits
* <tt>null</tt> values and the <tt>null</tt> key.  (The <tt>HashMap</tt>
* class is roughly equivalent to <tt>Hashtable</tt>, except that it is
* unsynchronized and permits nulls.)  This class makes no guarantees as to
* the order of the map; in particular, it does not guarantee that the order
* will remain constant over time.
*
* <p>This implementation provides constant-time performance for the basic
* operations (<tt>get</tt> and <tt>put</tt>), assuming the hash function
* disperses the elements properly among the buckets.  Iteration over
* collection views requires time proportional to the "capacity" of the
* <tt>HashMap</tt> instance (the number of buckets) plus its size (the number
* of key-value mappings).  Thus, it's very important not to set the initial
* capacity too high (or the load factor too low) if iteration performance is
* important.
*
* <p>An instance of <tt>HashMap</tt> has two parameters that affect its
* performance: <i>initial capacity</i> and <i>load factor</i>.  The
* <i>capacity</i> is the number of buckets in the hash table, and the initial
* capacity is simply the capacity at the time the hash table is created.  The
* <i>load factor</i> is a measure of how full the hash table is allowed to
* get before its capacity is automatically increased.  When the number of
* entries in the hash table exceeds the product of the load factor and the
* current capacity, the hash table is <i>rehashed</i> (that is, internal data
* structures are rebuilt) so that the hash table has approximately twice the
* number of buckets.
*
* <p>As a general rule, the default load factor (.75) offers a good
* tradeoff between time and space costs.  Higher values decrease the
* space overhead but increase the lookup cost (reflected in most of
* the operations of the <tt>HashMap</tt> class, including
* <tt>get</tt> and <tt>put</tt>).  The expected number of entries in
* the map and its load factor should be taken into account when
* setting its initial capacity, so as to minimize the number of
* rehash operations.  If the initial capacity is greater than the
* maximum number of entries divided by the load factor, no rehash
* operations will ever occur.
*
* <p>If many mappings are to be stored in a <tt>HashMap</tt>
* instance, creating it with a sufficiently large capacity will allow
* the mappings to be stored more efficiently than letting it perform
* automatic rehashing as needed to grow the table.  Note that using
* many keys with the same {@code hashCode()} is a sure way to slow
* down performance of any hash table. To ameliorate impact, when keys
* are {@link Comparable}, this class may use comparison order among
* keys to help break ties.
*
* <p><strong>Note that this implementation is not synchronized.</strong>
* If multiple threads access a hash map concurrently, and at least one of
* the threads modifies the map structurally, it <i>must</i> be
* synchronized externally.  (A structural modification is any operation
* that adds or deletes one or more mappings; merely changing the value
* associated with a key that an instance already contains is not a
* structural modification.)  This is typically accomplished by
* synchronizing on some object that naturally encapsulates the map.
*
* If no such object exists, the map should be "wrapped" using the
* {@link Collections#synchronizedMap Collections.synchronizedMap}
* method.  This is best done at creation time, to prevent accidental
* unsynchronized access to the map:<pre>
*   Map m = Collections.synchronizedMap(new HashMap(...));</pre>
*
* <p>The iterators returned by all of this class's "collection view methods"
* are <i>fail-fast</i>: if the map is structurally modified at any time after
* the iterator is created, in any way except through the iterator's own
* <tt>remove</tt> method, the iterator will throw a
* {@link ConcurrentModificationException}.  Thus, in the face of concurrent
* modification, the iterator fails quickly and cleanly, rather than risking
* arbitrary, non-deterministic behavior at an undetermined time in the
* future.
*
* <p>Note that the fail-fast behavior of an iterator cannot be guaranteed
* as it is, generally speaking, impossible to make any hard guarantees in the
* presence of unsynchronized concurrent modification.  Fail-fast iterators
* throw <tt>ConcurrentModificationException</tt> on a best-effort basis.
* Therefore, it would be wrong to write a program that depended on this
* exception for its correctness: <i>the fail-fast behavior of iterators
* should be used only to detect bugs.</i>
*
* <p>This class is a member of the
* <a href="{@docRoot}/../technotes/guides/collections/index.html">
* Java Collections Framework</a>.
*
* @param <K> the type of keys maintained by this map
* @param <V> the type of mapped values
*
* @author  Doug Lea
* @author  Josh Bloch
* @author  Arthur van Hoff
* @author  Neal Gafter
* @see     Object#hashCode()
* @see     Collection
* @see     Map
* @see     TreeMap
* @see     Hashtable
* @since   1.2
*/
class HashMap extends AbstractMap implements Map {
  /**
   * The table, initialized on first use, and resized as
   * necessary. When allocated, length is always a power of two.
   * (We also tolerate length zero in some operations to allow
   * bootstrapping mechanics that are currently not needed.)
   *
   * @var  HashMapNode[]  $table
   */
  private $table = [];

  /**
   * Holds cached entrySet(). Note that AbstractMap fields are used
   * for keySet() and values().
   *
   * @var  Set  $entrySet
   */
  private $entrySet;

  /**
   * @param  $value
   *
   * @return  int
   */
  private static function hash($value): int {
    return crc32(serialize($value));
  }

  /* ---------------- Public operations -------------- */

  /**
   * Implements Map.putAll and Map constructor
   *
   * @param  Map   $m      The map
   * @param  bool  $evict  False when initially constructing this map, else
   * true (relayed to method afterNodeInsertion).
   */
  private function putMapEntries(Map $m, bool $evict): void {
    if($m->size() > 0) {
      foreach($m->entrySet() as $e) {
        /** @var  Entry  $e */
        $key = $e->getKey();
        $value = $e->getValue();
        $this->putVal(self::hash($key), $key, $value, false, $evict);
      }
    }
  }

  /**
   * Returns the number of key-value mappings in this map.
   *
   * @return  int  The number of key-value mappings in this map
   */
  public function size(): int {
    return count($this->table);
  }

  /**
   * Returns <tt>true</tt> if this map contains no key-value mappings.
   *
   * @return  bool  <tt>true</tt> if this map contains no key-value mappings
   */
  public function isEmpty(): bool {
    return $this->size() === 0;
  }

  /**
   * Returns the value to which the specified key is mapped,
   * or {@code null} if this map contains no mapping for the key.
   *
   * <p>More formally, if this map contains a mapping from a key
   * {@code k} to a value {@code v} such that {@code (key==null ? k==null :
   * key.equals(k))}, then this method returns {@code v}; otherwise
   * it returns {@code null}.  (There can be at most one such mapping.)
   *
   * <p>A return value of {@code null} does not <i>necessarily</i>
   * indicate that the map contains no mapping for the key; it's also
   * possible that the map explicitly maps the key to {@code null}.
   * The {@link #containsKey containsKey} operation may be used to
   * distinguish these two cases.
   *
   * @param  mixed  $key
   *
   * @return  mixed
   *
   * @see #put(Object, Object)
   */
  public function get($key) {
    return ($e = $this->getNode($key)) === null ? null : $e->getValue();
  }

  /**
   * Implements Map.get and related methods
   *
   * @param  mixed  $key  The key
   *
   * @return  HashMapNode|null  The node, or null if none
   */
  private function getNode($key): ?HashMapNode {
    $hash = self::hash($key);

    $n = $this->size();

    if($n > 0 && ($first = $this->table[$hash] ?? null) !== null) {
      if($first->getHash() === $hash && $first->getKey() === $key) {
        return $first;
      }

      if(($e = $first->getNext()) !== null) {
        do {
          if($e->getHash() === $hash && $e->getKey() === $key) {
            return $e;
          }
        } while(($e = $e->getNext()) !== null);
      }
    }

    return null;
  }

  /**
   * Returns <tt>true</tt> if this map contains a mapping for the
   * specified key.
   *
   * @param  mixed  $key  The key whose presence in this map is to be tested
   *
   * @return  bool  <tt>true</tt> if this map contains a mapping for the specified key.
   */
  public function containsKey($key): bool {
    return $this->getNode($key) !== null;
  }

  /**
   * Associates the specified value with the specified key in this map.
   * If the map previously contained a mapping for the key, the old
   * value is replaced.
   *
   * @param  mixed  $key    The key with which the specified value is to be associated
   * @param  mixed  $value  The value to be associated with the specified key
   *
   * @return  mixed  The previous value associated with <tt>key</tt>, or
   *         <tt>null</tt> if there was no mapping for <tt>key</tt>.
   *         (A <tt>null</tt> return can also indicate that the map
   *         previously associated <tt>null</tt> with <tt>key</tt>.)
   */
  public function put($key, $value) {
    return $this->putVal(self::hash($key), $key, $value, false, true);
  }

  /**
   * Implements Map.put and related methods
   *
   * @param  int  $hash            Hash for key
   * @param  mixed  $key           The key
   * @param  mixed  $value         The value to put
   * @param  bool   $onlyIfAbsent  If true, don't change existing value
   * @param  bool   $evict         If false, the table is in creation mode.
   *
   * @return  mixed  The previous value, or null if none
   */
  private function putVal(int $hash, $key, $value, bool $onlyIfAbsent, bool $evict) {
    $e = null;

    if(($p = $this->table[$hash] ?? null) === null) {
      $this->table[$hash] = $this->newNode($hash, $key, $value, null);
    } else {
      if($p->getHash() === $hash && $p->getKey() === $key) {
        $e = $p;
      } else {
        for($binCount = 0; ; ++$binCount) {
          if(($e = $p->getNext()) === null) {
            $p->next = $this->newNode($hash, $key, $value, null);

            break;
          }

          if($e->getHash() === $hash && $e->getKey() === $key) {
            break;
          }

          $p = $e;
        }
      }

      if($e !== null) { // existing mapping for key
        $oldValue = $e->getValue();

        if(!$onlyIfAbsent || $oldValue === null) {
          $e->setValue($value);
        }

        $this->afterNodeAccess($e);
        return $oldValue;
      }
    }

    $this->afterNodeInsertion($evict);
    return null;
  }

  /**
   * Copies all of the mappings from the specified map to this map.
   * These mappings will replace any mappings that this map had for
   * any of the keys currently in the specified map.
   *
   * @param  Map  $m  Mappings to be stored in this map
   *
   * @return  void
   */
  public function putAll(Map $m): void {
    $this->putMapEntries($m, true);
  }

  /**
   * Removes the mapping for the specified key from this map if present.
   *
   * @param  mixed  $key  The key whose mapping is to be removed from the map
   *
   * @return  mixed  The previous value associated with <tt>key</tt>, or
   *         <tt>null</tt> if there was no mapping for <tt>key</tt>.
   *         (A <tt>null</tt> return can also indicate that the map
   *         previously associated <tt>null</tt> with <tt>key</tt>.)
   */
  public function remove($key) {
    return ($e = $this->removeNode($key, null, false)) === null ? null : $e->getValue();
  }

  /**
   * Implements Map.remove and related methods
   *
   * @param  mixed  $key         The key
   * @param  mixed  $value       The value to match if matchValue, else ignored
   * @param  bool   $matchValue  If true only remove if value is equal
   *
   * @return  HashMapNode  The node, or null if none
   */
  private function removeNode($key, $value, bool $matchValue) {
    $hash = self::hash($key);

    if(($p = $this->table[$hash] ?? null) !== null) {
      $node = null;

      if($p->getHash() === $hash && $p->getKey() === $key) {
        $node = $p;
      } else if(($e = $p->getNext()) !== null) {
        do {
          if($e->getHash() === $hash && $e->getKey() === $key) {
            $node = $e;
            break;
          }

          $p = $e;
        } while(($e = $e->getNext()) !== null);
      }

      if($node !== null && (!$matchValue || $node->getValue() === $value)) {
        if($node === $p) {
          $this->table[$hash] = $node->getNext();

          if($this->table[$hash] === null) {
            unset($this->table[$hash]);
          }
        } else {
          $p->next = $node->getNext();
        }

        $this->afterNodeRemoval($node);
        return $node;
      }
    }

    return null;
  }

  /**
   * Removes all of the mappings from this map.
   * The map will be empty after this call returns.
   *
   * @return  void
   */
  public function clear(): void {
    $this->table = [];
  }

  /**
   * Returns <tt>true</tt> if this map maps one or more keys to the
   * specified value.
   *
   * @param  mixed  $value  The value whose presence in this map is to be tested
   *
   * @return  bool  <tt>true</tt> if this map maps one or more keys to the
   *                specified value
   */
  public function containsValue($value): bool {
    foreach($this->table as $node) {
      for($e = $node; $e !== null; $e = $e->getNext()) {
        if($e->getValue() === $value) {
          return true;
        }
      }
    }

    return false;
  }

  /**
   * Returns a {@link Set} view of the keys contained in this map.
   * The set is backed by the map, so changes to the map are
   * reflected in the set, and vice-versa.  If the map is modified
   * while an iteration over the set is in progress (except through
   * the iterator's own <tt>remove</tt> operation), the results of
   * the iteration are undefined.  The set supports element removal,
   * which removes the corresponding mapping from the map, via the
   * <tt>Iterator.remove</tt>, <tt>Set.remove</tt>,
   * <tt>removeAll</tt>, <tt>retainAll</tt>, and <tt>clear</tt>
   * operations.  It does not support the <tt>add</tt> or <tt>addAll</tt>
   * operations.
   *
   * @return  Set  A set view of the keys contained in this map
   */
  public function keySet(): Set {
    $ks = $this->keySet;

    if($ks === null) {
      $ks = new HashMapKeySet($this, $this->table);
      $this->keySet = $ks;
    }

    return $ks;
  }

  /**
   * Returns a {@link Collection} view of the values contained in this map.
   * The collection is backed by the map, so changes to the map are
   * reflected in the collection, and vice-versa.  If the map is
   * modified while an iteration over the collection is in progress
   * (except through the iterator's own <tt>remove</tt> operation),
   * the results of the iteration are undefined.  The collection
   * supports element removal, which removes the corresponding
   * mapping from the map, via the <tt>Iterator.remove</tt>,
   * <tt>Collection.remove</tt>, <tt>removeAll</tt>,
   * <tt>retainAll</tt> and <tt>clear</tt> operations.  It does not
   * support the <tt>add</tt> or <tt>addAll</tt> operations.
   *
   * @return  Collection  A view of the values contained in this map
   */
  public function values(): Collection {
    $vs = $this->values;

    if($vs === null) {
      $vs = new HashMapValues($this, $this->table);
      $this->values = $vs;
    }

    return $vs;
  }

  /**
   * Returns a {@link Set} view of the mappings contained in this map.
   * The set is backed by the map, so changes to the map are
   * reflected in the set, and vice-versa.  If the map is modified
   * while an iteration over the set is in progress (except through
   * the iterator's own <tt>remove</tt> operation, or through the
   * <tt>setValue</tt> operation on a map entry returned by the
   * iterator) the results of the iteration are undefined.  The set
   * supports element removal, which removes the corresponding
   * mapping from the map, via the <tt>Iterator.remove</tt>,
   * <tt>Set.remove</tt>, <tt>removeAll</tt>, <tt>retainAll</tt> and
   * <tt>clear</tt> operations.  It does not support the
   * <tt>add</tt> or <tt>addAll</tt> operations.
   *
   * @return  Set  A set view of the mappings contained in this map
   */
  public function entrySet(): Set {
    return ($es = $this->entrySet) === null ? ($this->entrySet = new HashMapEntrySet($this, $this->table)) : $es;
  }

  // Overrides of JDK8 Map extension methods

  /**
   * @param  mixed  $key
   * @param  mixed  $defaultValue
   *
   * @return  mixed
   */
  public function getOrDefault($key, $defaultValue) {
    return ($e = $this->getNode($key)) === null ? $defaultValue : $e->getValue();
  }

  /**
   * @param  mixed  $key
   * @param  mixed  $value
   *
   * @return  mixed
   */
  public function putIfAbsent($key, $value) {
    return $this->putVal(self::hash($key), $key, $value, true, true);
  }

  /**
   * @param  mixed  $key
   * @param  mixed  $value
   *
   * @return  mixed|null
   */
  public function replace($key, $value) {
    if(($e = $this->getNode($key)) !== null) {
      $oldValue = $e->getValue();
      $e->setValue($value);
      $this->afterNodeAccess($e);
      return $oldValue;
    }

    return null;
  }

  /**
   * @param  mixed  $key
   * @param  Func   $mappingFunction
   *
   * @return  mixed|null
   */
  public function computeIfAbsent($key, Func $mappingFunction) {
    $hash = self::hash($key);
    $old = null;

    if(($first = $this->table[$hash] ?? null) !== null) {
      $e = $first;
      do {
        if($e->getHash() === $hash && $e->getKey() === $key) {
          $old = $e;
          break;
        }
      } while(($e = $e->getNext()) !== null);

      if($old !== null && ($oldValue = $old->getValue()) !== null) {
        $this->afterNodeAccess($old);
        return $oldValue;
      }
    }

    $v = $mappingFunction->apply($key);
    if($v === null) {
      return null;
    } else if($old !== null) {
      $old->setValue($v);
      $this->afterNodeAccess($old);
      return $v;
    } else {
      $this->table[$hash] = $this->newNode($hash, $key, $v, $first);
    }

    $this->afterNodeInsertion(true);
    return $v;
  }

  /**
   * @param  mixed       $key
   * @param  BiFunction  $remappingFunction
   *
   * @return  mixed|null
   */
  public function computeIfPresent($key, BiFunction $remappingFunction) {
    if(($e = $this->getNode($key)) !== null && ($oldValue = $e->getValue()) !== null) {
      $v = $remappingFunction->apply($key, $oldValue);

      if($v !== null) {
        $e->setValue($v);
        $this->afterNodeAccess($e);
        return $v;
      } else {
        $this->removeNode($key, null, false);
      }
    }

    return null;
  }

  /**
   * @param  mixed       $key
   * @param  BiFunction  $remappingFunction
   *
   * @return  mixed
   */
  public function compute($key, BiFunction $remappingFunction) {
    $hash = self::hash($key);
    $old = null;

    if(($first = $this->table[$hash] ?? null) !== null) {
      $e = $first;

      do {
        if($e->getHash() === $hash && $e->getKey() === $key) {
          $old = $e;
          break;
        }

      } while(($e = $e->getNext()) !== null);
    }

    $oldValue = ($old === null) ? null : $old->getValue();
    $v = $remappingFunction->apply($key, $oldValue);

    if($old !== null) {
      if($v !== null) {
        $old->setValue($v);
        $this->afterNodeAccess($old);
      } else {
        $this->removeNode($key, null, false);
      }
    } elseif($v !== null) {
      $this->table[$hash] = $this->newNode($hash, $key, $v, $first);
      $this->afterNodeInsertion(true);
    }

    return $v;
  }

  /**
   * @param  mixed       $key
   * @param  mixed       $value
   * @param  BiFunction  $remappingFunction
   *
   * @return  mixed
   */
  public function merge($key, $value, BiFunction $remappingFunction) {
    $hash = self::hash($key);
    $old = null;

    if(($first = $this->table[$hash] ?? null) !== null) {
      $e = $first;

      do {
        if($e->getHash() === $hash && $e->getKey() === $key) {
          $old = $e;
          break;
        }
      } while(($e = $e->getNext()) !== null);
    }

    if($old !== null) {
      if($old->getValue() !== null) {
        $v = $remappingFunction->apply($old->getValue(), $value);
      } else {
        $v = $value;
      }

      if($v !== null) {
        $old->setValue($v);
        $this->afterNodeAccess($old);
      } else {
        $this->removeNode($key, null, false);
      }

      return $v;
    }

    if($value !== null) {
      $this->table[$hash] = $this->newNode($hash, $key, $value, $first);
      $this->afterNodeInsertion(true);
    }

    return $value;
  }

  /**
   * @param  BiConsumer  $action
   */
  public function each(BiConsumer $action): void {
    foreach($this->table as $node) {
      for($e = $node; $e !== null; $e = $e->getNext()) {
        $action->accept($e->getKey(), $e->getValue());
      }
    }
  }

  /**
   * @param  BiFunction  $function
   */
  public function replaceAll(BiFunction $function): void {
    foreach($this->table as $node) {
      for($e = $node; $e !== null; $e = $e->getNext()) {
        $e->setValue($function->apply($e->getKey(), $e->getValue()));
      }
    }
  }

  /* ------------------------------------------------------------ */
  // LinkedHashMap support


  /*
   * The following package-protected methods are designed to be
   * overridden by LinkedHashMap, but not by any other subclass.
   * Nearly all other internal methods are also package-protected
   * but are declared final, so can be used by LinkedHashMap, view
   * classes, and HashSet.
   */

  /**
   * @param  int               $hash
   * @param                    $key
   * @param                    $value
   * @param  HashMapNode|null  $next
   *
   * @return  HashMapNode
   */
  private function newNode(int $hash, $key, $value, ?HashMapNode $next): HashMapNode {
    return new HashMapNode($hash, $key, $value, $next);
  }

  // Callbacks to allow LinkedHashMap post-actions

  /**
   * @param  HashMapNode  $p
   */
  protected function afterNodeAccess(HashMapNode $p): void { }

  /**
   * @param  bool  $evict
   */
  protected function afterNodeInsertion(bool $evict): void { }

  /**
   * @param  HashMapNode  $p
   */
  protected function afterNodeRemoval(HashMapNode $p): void { }
}
