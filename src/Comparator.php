<?php declare(strict_types=1); namespace BapCat\Collection;
use BapCat\Collection\Functions\Func;

/**
 * A comparison function, which imposes a <i>total ordering</i> on some
 * collection of objects.  Comparators can be passed to a sort method (such
 * as {@link Collections#sort(List,Comparator) Collections.sort} or {@link
 * Arrays#sort(Object[],Comparator) Arrays.sort}) to allow precise control
 * over the sort order.  Comparators can also be used to control the order of
 * certain data structures (such as {@link SortedSet sorted sets} or {@link
 * SortedMap sorted maps}), or to provide an ordering for collections of
 * objects that don't have a {@link Comparable natural ordering}.<p>
 *
 * The ordering imposed by a comparator <tt>c</tt> on a set of elements
 * <tt>S</tt> is said to be <i>consistent with equals</i> if and only if
 * <tt>c.compare(e1, e2)==0</tt> has the same boolean value as
 * <tt>e1.equals(e2)</tt> for every <tt>e1</tt> and <tt>e2</tt> in
 * <tt>S</tt>.<p>
 *
 * Caution should be exercised when using a comparator capable of imposing an
 * ordering inconsistent with equals to order a sorted set (or sorted map).
 * Suppose a sorted set (or sorted map) with an explicit comparator <tt>c</tt>
 * is used with elements (or keys) drawn from a set <tt>S</tt>.  If the
 * ordering imposed by <tt>c</tt> on <tt>S</tt> is inconsistent with equals,
 * the sorted set (or sorted map) will behave "strangely."  In particular the
 * sorted set (or sorted map) will violate the general contract for set (or
 * map), which is defined in terms of <tt>equals</tt>.<p>
 *
 * For example, suppose one adds two elements {@code a} and {@code b} such that
 * {@code (a.equals(b) && c.compare(a, b) != 0)}
 * to an empty {@code TreeSet} with comparator {@code c}.
 * The second {@code add} operation will return
 * true (and the size of the tree set will increase) because {@code a} and
 * {@code b} are not equivalent from the tree set's perspective, even though
 * this is contrary to the specification of the
 * {@link Set#add Set.add} method.<p>
 *
 * Note: It is generally a good idea for comparators to also implement
 * <tt>java.io.Serializable</tt>, as they may be used as ordering methods in
 * serializable data structures (like {@link TreeSet}, {@link TreeMap}).  In
 * order for the data structure to serialize successfully, the comparator (if
 * provided) must implement <tt>Serializable</tt>.<p>
 *
 * For the mathematically inclined, the <i>relation</i> that defines the
 * <i>imposed ordering</i> that a given comparator <tt>c</tt> imposes on a
 * given set of objects <tt>S</tt> is:<pre>
 *       {(x, y) such that c.compare(x, y) &lt;= 0}.
 * </pre> The <i>quotient</i> for this total order is:<pre>
 *       {(x, y) such that c.compare(x, y) == 0}.
 * </pre>
 *
 * It follows immediately from the contract for <tt>compare</tt> that the
 * quotient is an <i>equivalence relation</i> on <tt>S</tt>, and that the
 * imposed ordering is a <i>total order</i> on <tt>S</tt>.  When we say that
 * the ordering imposed by <tt>c</tt> on <tt>S</tt> is <i>consistent with
 * equals</i>, we mean that the quotient for the ordering is the equivalence
 * relation defined by the objects' {@link Object#equals(Object)
 * equals(Object)} method(s):<pre>
 *     {(x, y) such that x.equals(y)}. </pre>
 *
 * <p>Unlike {@code Comparable}, a comparator may optionally permit
 * comparison of null arguments, while maintaining the requirements for
 * an equivalence relation.
 *
 * <p>This interface is a member of the
 * <a href="{@docRoot}/../technotes/guides/collections/index.html">
 * Java Collections Framework</a>.
 *
 * @param <T> the type of objects that may be compared by this comparator
 *
 * @author  Josh Bloch
 * @author  Neal Gafter
 * @see Comparable
 * @see java.io.Serializable
 * @since 1.2
 */
abstract class Comparator {
  /**
   * Compares its two arguments for order.  Returns a negative integer,
   * zero, or a positive integer as the first argument is less than, equal
   * to, or greater than the second.<p>
   *
   * In the foregoing description, the notation
   * <tt>sgn(</tt><i>expression</i><tt>)</tt> designates the mathematical
   * <i>signum</i> function, which is defined to return one of <tt>-1</tt>,
   * <tt>0</tt>, or <tt>1</tt> according to whether the value of
   * <i>expression</i> is negative, zero or positive.<p>
   *
   * The implementor must ensure that <tt>sgn(compare(x, y)) ==
   * -sgn(compare(y, x))</tt> for all <tt>x</tt> and <tt>y</tt>.  (This
   * implies that <tt>compare(x, y)</tt> must throw an exception if and only
   * if <tt>compare(y, x)</tt> throws an exception.)<p>
   *
   * The implementor must also ensure that the relation is transitive:
   * <tt>((compare(x, y)&gt;0) &amp;&amp; (compare(y, z)&gt;0))</tt> implies
   * <tt>compare(x, z)&gt;0</tt>.<p>
   *
   * Finally, the implementor must ensure that <tt>compare(x, y)==0</tt>
   * implies that <tt>sgn(compare(x, z))==sgn(compare(y, z))</tt> for all
   * <tt>z</tt>.<p>
   *
   * It is generally the case, but <i>not</i> strictly required that
   * <tt>(compare(x, y)==0) == (x.equals(y))</tt>.  Generally speaking,
   * any comparator that violates this condition should clearly indicate
   * this fact.  The recommended language is "Note: this comparator
   * imposes orderings that are inconsistent with equals."
   *
   * @param  mixed  $o1  The first object to be compared.
   * @param  mixed  $o2  The second object to be compared.
   *
   * @return  int  A negative integer, zero, or a positive integer as the
   *               first argument is less than, equal to, or greater than the
   *               second.
   *
   * @throws NullPointerException if an argument is null and this
   *         comparator does not permit null arguments
   */
  abstract function compare($o1, $o2): int;

  /**
   * Returns a comparator that imposes the reverse ordering of this
   * comparator.
   *
   * @return  Comparator  A comparator that imposes the reverse ordering of this
   *                      comparator.
   */
  public function reversed(): Comparator {
    return Collections::reverseOrder($this);
  }

  /**
   * Returns a lexicographic-order comparator with another comparator.
   * If this <tt>Comparator</tt> considers two elements equal, i.e.
   * <tt>compare(a, b) == 0</tt>, <tt>other</tt> is used to determine the order.
   *
   * For example, to sort a collection of <tt>String</tt> based on the length
   * and then case-insensitive natural ordering, the comparator can be
   * composed using following code,
   *
   *     Comparator<String> cmp = Comparator.comparingInt(String::length)
   *             .thenComparing(String.CASE_INSENSITIVE_ORDER);
   *
   * @param  Comparator  $other  The other comparator to be used when this comparator
   *                             compares two objects that are equal.
   *
   * @return  Comparator  A lexicographic-order comparator composed of this and then the
   *                      other comparator
   *
   * @throws NullPointerException if the argument is null.
   */
  public function thenComparing(Comparator $other): Comparator {
    return new class($this, $other) extends Comparator {
      /**
       * @var  Comparator
       */
      private $parent;

      /**
       * @var  Comparator
       */
      private $other;

      /**
       * @param  Comparator  $parent
       * @param  Comparator  $other
       */
      public function __construct(Comparator $parent, Comparator $other) {
        $this->parent = $parent;
        $this->other = $other;
      }

      /**
       * @param  mixed  $o1
       * @param  mixed  $o2
       *
       * @return  int
       */
      public function compare($o1, $o2) : int {
        $res = $this->parent->compare($o1, $o2);
        return ($res !== 0) ? $res : $this->other->compare($o1, $o2);
      }
    };
  }

  /**
   * Returns a comparator that imposes the reverse of the <em>natural
   * ordering</em>.
   *
   * @return  Comparator  A comparator that imposes the reverse of the <i>natural
   *                      ordering</i> on <tt>Comparable</tt> objects.
   */
  public static function reverseOrder(): Comparator {
    return Collections::reverseOrder();
  }

  /**
   * Returns a null-friendly comparator that considers <tt>null</tt> to be
   * less than non-null. When both are <tt>null</tt>, they are considered
   * equal. If both are non-null, the specified <tt>Comparator</tt> is used
   * to determine the order. If the specified comparator is <tt>null</tt>,
   * then the returned comparator considers all non-null values to be equal.
   *
   * @param  Comparator  $comparator  A <tt>Comparator</tt> for comparing non-null values
   *
   * @return  Comparator  A comparator that considers <tt>null</tt> to be less than
   *                      non-null, and compares non-null objects with the supplied
   *                      <tt>Comparator</tt>.
   */
  public static function nullsFirst(Comparator $comparator): Comparator {
    return new NullComparator(true, $comparator);
  }

  /**
   * Returns a null-friendly comparator that considers <tt>null</tt> to be
   * greater than non-null. When both are <tt>null</tt>, they are considered
   * equal. If both are non-null, the specified <tt>Comparator</tt> is used
   * to determine the order. If the specified comparator is <tt>null</tt>,
   * then the returned comparator considers all non-null values to be equal.
   *
   * @param  Comparator  $comparator  A <tt>Comparator</tt> for comparing non-null values
   *
   * @return  Comparator  A comparator that considers <tt>null</tt> to be greater than
   *                      non-null, and compares non-null objects with the supplied
   *                      <tt>Comparator</tt>.
   */
  public static function nullsLast(Comparator $comparator): Comparator {
    return new NullComparator(false, $comparator);
  }

  /**
   * Accepts a function that extracts a sort key from a type {@code T}, and
   * returns a {@code Comparator<T>} that compares by that sort key using
   * the specified {@link Comparator}.
   *
   * For example, to obtain a {@code Comparator} that compares {@code
   * Person} objects by their last name ignoring case differences,
   *
   *     Comparator<Person> cmp = Comparator.comparing(
   *             Person::getLastName,
   *             String.CASE_INSENSITIVE_ORDER);
   *
   * @param  Func        $keyExtractor   The function used to extract the sort key
   * @param  Comparator  $keyComparator  The {@code Comparator} used to compare the sort key
   *
   * @return  Comparator  A comparator that compares by an extracted key using the
   *                      specified {@code Comparator}
   *
   * @throws NullPointerException if either argument is null
   */
  public static function comparing(Func $keyExtractor, Comparator $keyComparator): Comparator {
    return new class($keyExtractor, $keyComparator) extends Comparator {
      /**
       * @var  Func
       */
      private $keyExtractor;

      /**
       * @var  Comparator
       */
      private $keyComparator;

      /**
       * @param  Func        $keyExtractor
       * @param  Comparator  $keyComparator
       */
      public function __construct(Func $keyExtractor, Comparator $keyComparator) {
        $this->keyExtractor  = $keyExtractor;
        $this->keyComparator = $keyComparator;
      }

      /**
       * @param  mixed  $o1
       * @param  mixed  $o2
       *
       * @return  int
       */
      public function compare($o1, $o2): int {
        return $this->keyComparator->compare($this->keyExtractor->apply($o1), $this->keyExtractor->apply($o2));
      }
    };
  }
}
