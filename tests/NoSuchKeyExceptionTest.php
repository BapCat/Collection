<?php

use LordMonoxide\Collection\Exceptions\NoSuchKeyException;

class NoSuchKeyExceptionTest extends PHPUnit_Framework_TestCase {
  public function testGetKey() {
    $ex = new NoSuchKeyException('Test');
    $this->assertEquals('Test', $ex->getKey());
  }
}
