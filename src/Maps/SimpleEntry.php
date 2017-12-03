<?php declare(strict_types=1); namespace BapCat\Collection\Maps;

/**
 * An Entry maintaining a key and a value.  The value may be
 * changed using the <tt>setValue</tt> method.  This class
 * facilitates the process of building custom map
 * implementations. For example, it may be convenient to return
 * arrays of <tt>SimpleEntry</tt> instances in method
 * <tt>Map.entrySet().toArray</tt>.
 *
 * @since 1.6
 */
class SimpleEntry implements Entry {
  /**
   * @var  mixed
   */
  private $key;

  /**
   * @var  mixed
   */
  private $val;

  /**
   * Creates an entry representing a mapping from the specified
   * key to the specified value.
   *
   * @param  mixed  $key  The key represented by this entry
   * @param  mixed  $val  The value represented by this entry
   */
  public function __construct($key, $val) {
    $this->key = $key;
    $this->val = $val;
  }

  /**
   * Returns the key corresponding to this entry.
   *
   * @return  mixed  The key corresponding to this entry
   */
  public function getKey() {
    return $this->key;
  }

  /**
   * Returns the value corresponding to this entry.
   *
   * @return  mixed  The value corresponding to this entry
   */
  public function getValue() {
    return $this->val;
  }

  /**
   * Replaces the value corresponding to this entry with the specified
   * value.
   *
   * @param  mixed  $val  The new value to be stored in this entry
   *
   * @return  mixed  The old value corresponding to the entry
   */
  public function setValue($val) {
    $oldValue = $this->val;
    $this->val = $val;
    return $oldValue;
  }

  /**
   * Compares the specified object with this entry for equality.
   * Returns {@code true} if the given object is also a map entry and
   * the two entries represent the same mapping.  More formally, two
   * entries {@code e1} and {@code e2} represent the same mapping
   * if<pre>
   *   (e1.getKey()==null ?
   *    e2.getKey()==null :
   *    e1.getKey().equals(e2.getKey()))
   *   &amp;&amp;
   *   (e1.getValue()==null ?
   *    e2.getValue()==null :
   *    e1.getValue().equals(e2.getValue()))</pre>
   * This ensures that the {@code equals} method works properly across
   * different implementations of the {@code Map.Entry} interface.
   *
   * @param  mixed  $o  The object to be compared for equality with this map entry
   *
   * @return  bool  {@code true} if the specified object is equal to this map entry
   *
   * @see    #hashCode
   */
  public function equals($o): bool {
    if(!($o instanceof Entry)) {
      return false;
    }

    return $this->hashCode() === $o->hashCode();
  }

  /**
   * Returns the hash code value for this map entry.
   * This ensures that {@code e1.equals(e2)} implies that
   * {@code e1.hashCode()==e2.hashCode()} for any two Entries
   * {@code e1} and {@code e2}, as required by the general
   * contract of {@link Object#hashCode}.
   *
   * @return  string  The hash code value for this map entry
   *
   * @see    #equals
   */
  public function hashCode(): string {
    return md5(serialize($this));
  }

  /**
   * Returns a String representation of this map entry.  This
   * implementation returns the string representation of this
   * entry's key followed by the equals character ("<tt>=</tt>")
   * followed by the string representation of this entry's value.
   *
   * @return  string  A String representation of this map entry
   */
  public function toString(): string {
    return "{$this->key}={$this->val}";
  }
}
