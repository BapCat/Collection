<?php declare(strict_types=1); namespace BapCat\Collection;

/**
 * See {@link Comparator::nullsFirst()} and {@link Comparator::nullsLast()}
 */
class NullComparator extends Comparator {
  /**
   * @var  bool
   */
  private $nullFirst;

  // if null, non-null Ts are considered equal
  /**
   * @var  Comparator
   */
  private $real;

  /**
   * @param  bool        $nullFirst
   * @param  Comparator  $real
   */
  public function __construct(bool $nullFirst, Comparator $real) {
    $this->nullFirst = $nullFirst;
    $this->real = $real;
  }

  /**
   * @param  mixed  $a
   * @param  mixed  $b
   *
   * @return  int
   */
  public function compare($a, $b): int {
    if($a === null) {
      return ($b === null) ? 0 : ($this->nullFirst ? -1 : 1);
    } else if($b === null) {
      return $this->nullFirst ? 1: -1;
    } else {
      return ($this->real === null) ? 0 : $this->real->compare($a, $b);
    }
  }

  /**
   * @param  Comparator  $other
   *
   * @return  Comparator
   */
  public function thenComparing(Comparator $other): Comparator {
    return new NullComparator($this->nullFirst, $this->real == null ? $other : $this->real->thenComparing($other));
  }

  /**
   * @return  Comparator
   */
  public function reversed(): Comparator {
    return new NullComparator(!$this->nullFirst, $this->real == null ? null : $this->real->reversed());
  }
}
