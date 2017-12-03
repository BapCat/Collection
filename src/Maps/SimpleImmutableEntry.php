<?php declare(strict_types=1); namespace BapCat\Collection\Maps;
use BapCat\Collection\UnsupportedOperationException;


/**
* An Entry maintaining an immutable key and value.  This class
* does not support method <tt>setValue</tt>.  This class may be
* convenient in methods that return thread-safe snapshots of
* key-value mappings.
*
* @since 1.6
*/
class SimpleImmutableEntry implements Entry {
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
   * value (optional operation).  This implementation simply throws
   * <tt>UnsupportedOperationException</tt>, as this class implements
   * an <i>immutable</i> map entry.
   *
   * @param  mixed  $value  The new value to be stored in this entry
   *
   * @return  mixed
   *
   * @throws UnsupportedOperationException always
   */
  public function setValue($value) {
    throw new UnsupportedOperationException();
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
   * Returns the hash code value for this map entry.  The hash code
   * of a map entry {@code e} is defined to be: <pre>
   *   (e.getKey()==null   ? 0 : e.getKey().hashCode()) ^
   *   (e.getValue()==null ? 0 : e.getValue().hashCode())</pre>
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
