<?php declare(strict_types=1); namespace BapCat\Collection;

use RuntimeException;

/**
 * Signals that a method has been invoked at an illegal or
 * inappropriate time.  In other words, the Java environment or
 * Java application is not in an appropriate state for the requested
 * operation.
 */
class IllegalStateException extends RuntimeException {

}
