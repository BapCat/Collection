<?php

namespace Exceptions;

use BapCat\Collection\Exceptions\NoSuchKeyException;
use PHPUnit\Framework\TestCase;

class NoSuchKeyExceptionTest extends TestCase {
  public function testGetKey() {
    $ex = new NoSuchKeyException('Test');
    $this->assertEquals('Test', $ex->getKey());
  }
}
