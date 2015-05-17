<?php namespace LordMonoxide\Collection\Exceptions;

use Exception;

/**
 * An exception indicating that the desired key does not exist in the collection
 */
class NoSuchKeyException extends Exception {
  private $key;
  
  /**
   * Constructs a new NoSuchKeyException
   * 
   * @param string    $key      The requested key
   * @param string    $message  (optional) A message to include with the exception
   * @param int       $code     (optional) A code to include with the exception
   * @param Exception $previous (optional) An exception to be wrapped by this one
   */
  public function __construct($key, $message = '', $code = 0, Exception $previous = null) {
    parent::__construct($message, $code, $previous);
    $this->key = $key;
  }
  
  /**
   * Gets the requested key which had no associated item
   */
  public function getKey() {
    return $this->key;
  }
}
