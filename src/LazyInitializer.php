<?php namespace BapCat\Collection;

/**
 * A class used to defer loading of collection values
 */
class LazyInitializer {
  /**
   * @var callable  $initializer A callback to execute on the
   *                             first request of the given value
   */
  private $initializer = null;
  
  /**
   * Constructs a new instance of LazyInitializer
   * 
   * @param callable  $initializer  A callback to execute on the
   *                                first request of the given value
   */
  public function __construct(callable $initializer) {
    $this->initializer = $initializer;
  }
  
  /**
   * Invokes the callable registered for this value
   * 
   * @param   mixed $key  The key
   * 
   * @returns mixed The lazy-loaded value
   */
  public function __invoke($key) {
    $initializer = $this->initializer;
    return $initializer($key);
  }
}
