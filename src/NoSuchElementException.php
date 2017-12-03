<?php declare(strict_types=1); namespace BapCat\Collection;

use RuntimeException;

/**
 * Thrown by various accessor methods to indicate that the element being requested
 * does not exist.
 */
class NoSuchElementException extends RuntimeException {

}
