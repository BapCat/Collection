<?php declare(strict_types=1); namespace BapCat\Collection;

use RuntimeException;

/**
 * Thrown when an application attempts to use <tt>null</tt> in a
 * case where an object is required. These include:
 * <ul>
 * <li>Calling the instance method of a <tt>null</tt> object.
 * <li>Accessing or modifying the field of a <tt>null</tt> object.
 * <li>Taking the length of <tt>null</tt> as if it were an array.
 * <li>Accessing or modifying the slots of <tt>null</tt> as if it
 *     were an array.
 * <li>Throwing <tt>null</tt> as if it were a <tt>Throwable</tt>
 *     value.
 * </ul>
 * <p>
 * Applications should throw instances of this class to indicate
 * other illegal uses of the <tt>null<tt> object.
 */
class NullPointerException extends RuntimeException {

}
