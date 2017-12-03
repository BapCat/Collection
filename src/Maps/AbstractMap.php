<?php declare(strict_types=1); namespace BapCat\Collection\Maps;
use BapCat\Collection\Collection;
use BapCat\Collection\IllegalArgumentException;use BapCat\Collection\NullPointerException;use BapCat\Collection\Sets\Set;use BapCat\Collection\UnsupportedOperationException;

/**
 * This class provides a skeletal implementation of the <tt>Map</tt>
 * interface, to minimize the effort required to implement this interface.
 *
 * <p>To implement an unmodifiable map, the programmer needs only to extend this
 * class and provide an implementation for the <tt>entrySet</tt> method, which
 * returns a set-view of the map's mappings.  Typically, the returned set
 * will, in turn, be implemented atop <tt>AbstractSet</tt>.  This set should
 * not support the <tt>add</tt> or <tt>remove</tt> methods, and its iterator
 * should not support the <tt>remove</tt> method.
 *
 * <p>To implement a modifiable map, the programmer must additionally override
 * this class's <tt>put</tt> method (which otherwise throws an
 * <tt>UnsupportedOperationException</tt>), and the iterator returned by
 * <tt>entrySet().iterator()</tt> must additionally implement its
 * <tt>remove</tt> method.
 *
 * <p>The programmer should generally provide a void (no argument) and map
 * constructor, as per the recommendation in the <tt>Map</tt> interface
 * specification.
 *
 * <p>The documentation for each non-abstract method in this class describes its
 * implementation in detail.  Each of these methods may be overridden if the
 * map being implemented admits a more efficient implementation.
 *
 * <p>This class is a member of the
 * <a href="{@docRoot}/../technotes/guides/collections/index.html">
 * Java Collections Framework</a>.
 *
 * @param <K> the type of keys maintained by this map
 * @param <V> the type of mapped values
 *
 * @author  Josh Bloch
 * @author  Neal Gafter
 * @see Map
 * @see Collection
 * @since 1.2
 */
abstract class AbstractMap implements Map {
  // Query Operations

  /**
   * {@inheritdoc}
   *
   * @implSpec
   * This implementation returns <tt>entrySet().size()</tt>.
   */
  public function size(): int {
    return $this->entrySet()->size();
  }

  /**
   * {@inheritdoc}
   *
   * @implSpec
   * This implementation returns <tt>size() == 0</tt>.
   */
  public function isEmpty(): bool {
    return $this->size() === 0;
  }

  /**
   * {@inheritdoc}
   *
   * @implSpec
   * This implementation iterates over <tt>entrySet()</tt> searching
   * for an entry with the specified value.  If such an entry is found,
   * <tt>true</tt> is returned.  If the iteration terminates without
   * finding such an entry, <tt>false</tt> is returned.  Note that this
   * implementation requires linear time in the size of the map.
   *
   * @throws NullPointerException {@inheritdoc}
   */
  public function containsValue($value): bool {
    $i = $this->entrySet()->iterator();

    while($i->hasNext()) {
      $e = $i->next();

      if($e->getValue() === $value) {
        return true;
      }
    }

    return false;
  }

  /**
   * {@inheritdoc}
   *
   * @implSpec
   * This implementation iterates over <tt>entrySet()</tt> searching
   * for an entry with the specified key.  If such an entry is found,
   * <tt>true</tt> is returned.  If the iteration terminates without
   * finding such an entry, <tt>false</tt> is returned.  Note that this
   * implementation requires linear time in the size of the map; many
   * implementations will override this method.
   *
   * @throws NullPointerException {@inheritdoc}
   */
  public function containsKey($key): bool {
    $i = $this->entrySet()->iterator();

    while($i->hasNext()) {
      $e = $i->next();

      if($e->getKey() === $key) {
        return true;
      }
    }

    return false;
  }

  /**
   * {@inheritdoc}
   *
   * @implSpec
   * This implementation iterates over <tt>entrySet()</tt> searching
   * for an entry with the specified key.  If such an entry is found,
   * the entry's value is returned.  If the iteration terminates without
   * finding such an entry, <tt>null</tt> is returned.  Note that this
   * implementation requires linear time in the size of the map; many
   * implementations will override this method.
   *
   * @throws NullPointerException          {@inheritdoc}
   */
  public function get($key) {
    $i = $this->entrySet()->iterator();

    while($i->hasNext()) {
      $e = $i->next();

      if($e->getKey() === $key) {
        return $e->getValue();
      }
    }

    return null;
  }


  // Modification Operations

  /**
   * {@inheritdoc}
   *
   * @implSpec
   * This implementation always throws an
   * <tt>UnsupportedOperationException</tt>.
   *
   * @throws UnsupportedOperationException {@inheritdoc}
   * @throws NullPointerException          {@inheritdoc}
   * @throws IllegalArgumentException      {@inheritdoc}
   */
  public function put($key, $value) {
    throw new UnsupportedOperationException();
  }

  /**
   * {@inheritdoc}
   *
   * @implSpec
   * This implementation iterates over <tt>entrySet()</tt> searching for an
   * entry with the specified key.  If such an entry is found, its value is
   * obtained with its <tt>getValue</tt> operation, the entry is removed
   * from the collection (and the backing map) with the iterator's
   * <tt>remove</tt> operation, and the saved value is returned.  If the
   * iteration terminates without finding such an entry, <tt>null</tt> is
   * returned.  Note that this implementation requires linear time in the
   * size of the map; many implementations will override this method.
   *
   * <p>Note that this implementation throws an
   * <tt>UnsupportedOperationException</tt> if the <tt>entrySet</tt>
   * iterator does not support the <tt>remove</tt> method and this map
   * contains a mapping for the specified key.
   *
   * @throws UnsupportedOperationException {@inheritdoc}
   * @throws NullPointerException          {@inheritdoc}
   */
  public function remove($key) {
    $i = $this->entrySet()->iterator();
    $correctEntry = null;

    while($correctEntry === null && $i->hasNext()) {
      $e = $i->next();

      if($e->getKey() === $key) {
        $correctEntry = $e;
      }
    }

    $oldValue = null;
    if($correctEntry !== null) {
      $oldValue = $correctEntry->getValue();
      $i->remove();
    }

    return $oldValue;
  }


  // Bulk Operations

  /**
   * {@inheritdoc}
   *
   * @implSpec
   * This implementation iterates over the specified map's
   * <tt>entrySet()</tt> collection, and calls this map's <tt>put</tt>
   * operation once for each entry returned by the iteration.
   *
   * <p>Note that this implementation throws an
   * <tt>UnsupportedOperationException</tt> if this map does not support
   * the <tt>put</tt> operation and the specified map is nonempty.
   *
   * @throws UnsupportedOperationException {@inheritdoc}
   * @throws NullPointerException          {@inheritdoc}
   * @throws IllegalArgumentException      {@inheritdoc}
   */
  public function putAll(Map $m): void {
    foreach($m->entrySet() as $e) {
      /** @var  Entry  $e */
      $this->put($e->getKey(), $e->getValue());
    }
  }

  /**
   * {@inheritdoc}
   *
   * @implSpec
   * This implementation calls <tt>entrySet().clear()</tt>.
   *
   * <p>Note that this implementation throws an
   * <tt>UnsupportedOperationException</tt> if the <tt>entrySet</tt>
   * does not support the <tt>clear</tt> operation.
   *
   * @throws UnsupportedOperationException {@inheritdoc}
   */
  public function clear(): void {
    $this->entrySet()->clear();
  }


  // Views

  /**
   * Each of these fields are initialized to contain an instance of the
   * appropriate view the first time this view is requested.  The views are
   * stateless, so there's no reason to create more than one of each.
   *
   * <p>Since there is no synchronization performed while accessing these fields,
   * it is expected that java.util.Map view classes using these fields have
   * no non-final fields (or any fields at all except for outer-this). Adhering
   * to this rule would make the races on these fields benign.
   *
   * <p>It is also imperative that implementations read the field only once,
   * as in:
   *
   *     public Set<K> keySet() {
   *       Set<K> ks = keySet;  // single racy read
   *       if (ks == null) {
   *         ks = new AbstractMapKeySet();
   *         keySet = ks;
   *       }
   *       return ks;
   *     }
   *
   * @var  Set
   */
  protected $keySet;

  /**
   * @var  Collection
   */
  protected $values;

  /**
   * {@inheritdoc}
   *
   * @implSpec
   * This implementation returns a set that subclasses {@link AbstractSet}.
   * The subclass's iterator method returns a "wrapper object" over this
   * map's <tt>entrySet()</tt> iterator.  The <tt>size</tt> method
   * delegates to this map's <tt>size</tt> method and the
   * <tt>contains</tt> method delegates to this map's
   * <tt>containsKey</tt> method.
   *
   * <p>The set is created the first time this method is called,
   * and returned in response to all subsequent calls.  No synchronization
   * is performed, so there is a slight chance that multiple calls to this
   * method will not all return the same set.
   */
  public function keySet(): Set {
    if($this->keySet === null) {
      $this->keySet = new AbstractMapKeySet($this);
    }

    return $this->keySet;
  }

  /**
   * {@inheritdoc}
   *
   * @implSpec
   * This implementation returns a collection that subclasses {@link
   * AbstractCollection}.  The subclass's iterator method returns a
   * "wrapper object" over this map's <tt>entrySet()</tt> iterator.
   * The <tt>size</tt> method delegates to this map's <tt>size</tt>
   * method and the <tt>contains</tt> method delegates to this map's
   * <tt>containsValue</tt> method.
   *
   * <p>The collection is created the first time this method is called, and
   * returned in response to all subsequent calls.  No synchronization is
   * performed, so there is a slight chance that multiple calls to this
   * method will not all return the same collection.
   */
  public function values(): Collection {
    if($this->values === null) {
      $this->values = new ValueCollection($this);
    }

    return $this->values;
  }

  /**
   * @return  Set
   */
  public abstract function entrySet(): Set;


  // Comparison and hashing

  /**
   * Compares the specified object with this map for equality.  Returns
   * <tt>true</tt> if the given object is also a map and the two maps
   * represent the same mappings.  More formally, two maps <tt>m1</tt> and
   * <tt>m2</tt> represent the same mappings if
   * <tt>m1.entrySet().equals(m2.entrySet())</tt>.  This ensures that the
   * <tt>equals</tt> method works properly across different implementations
   * of the <tt>Map</tt> interface.
   *
   * @implSpec
   * This implementation first checks if the specified object is this map;
   * if so it returns <tt>true</tt>.  Then, it checks if the specified
   * object is a map whose size is identical to the size of this map; if
   * not, it returns <tt>false</tt>.  If so, it iterates over this map's
   * <tt>entrySet</tt> collection, and checks that the specified map
   * contains each mapping that this map contains.  If the specified map
   * fails to contain such a mapping, <tt>false</tt> is returned.  If the
   * iteration completes, <tt>true</tt> is returned.
   *
   * @param  mixed  $o  The object to be compared for equality with this map
   *
   * @return  bool  <tt>true</tt> if the specified object is equal to this map
   */
  public function equals($o): bool {
    if($o === $this) {
      return true;
    }

    if(!($o instanceof Map)) {
      return false;
    }

    /** @var  Map  $o */
    if($o->size() != $this->size()) {
      return false;
    }

    try {
      $i = $this->entrySet()->iterator();

      while($i->hasNext()) {
        $e = $i->next();
        $key = $e->getKey();
        $value = $e->getValue();

        if(!($o->get($key) === $value)) {
          return false;
        }
      }
    } catch(NullPointerException $unused) {
      return false;
    }

    return true;
  }

  /**
   * Returns the hash code value for this map.  The hash code of a map is
   * defined to be the sum of the hash codes of each entry in the map's
   * <tt>entrySet()</tt> view.  This ensures that <tt>m1.equals(m2)</tt>
   * implies that <tt>m1.hashCode()==m2.hashCode()</tt> for any two maps
   * <tt>m1</tt> and <tt>m2</tt>, as required by the general contract of
   * {@link Object#hashCode}.
   *
   * @implSpec
   * This implementation iterates over <tt>entrySet()</tt>, calling
   * {@link Map.Entry#hashCode hashCode()} on each element (entry) in the
   * set, and adding up the results.
   *
   * @return  int  The hash code value for this map
   * @see Map.Entry#hashCode()
   * @see Object#equals(Object)
   * @see Set#equals(Object)
   */
  public function hashCode(): int {
    $h = 0;
    $i = $this->entrySet()->iterator();

    while($i->hasNext()) {
      $h += $i->next()->hashCode();
    }

    return $h;
  }

  /**
   * Returns a string representation of this map.  The string representation
   * consists of a list of key-value mappings in the order returned by the
   * map's <tt>entrySet</tt> view's iterator, enclosed in braces
   * (<tt>"{}"</tt>).  Adjacent mappings are separated by the characters
   * <tt>", "</tt> (comma and space).  Each key-value mapping is rendered as
   * the key followed by an equals sign (<tt>"="</tt>) followed by the
   * associated value.  Keys and values are converted to strings as by
   * {@link String#valueOf(Object)}.
   *
   * @return  string  A string representation of this map
   */
  public function toString(): string {
    $i = $this->entrySet()->iterator();

    if(!$i->hasNext()) {
      return "{}";
    }

    $sb = '{';

    for(;;) {
      $e = $i->next();
      $key = $e->getKey();
      $value = $e->getValue();

      $sb .= $key === $this ? '(this Map)' : $key;
      $sb .= '=';
      $sb .= $value === $this ? '(this Map)' : $value;

      if(!$i->hasNext()) {
        return $sb . '}';
      }

      $sb .= ', ';
    }

    return $sb;
  }
}
