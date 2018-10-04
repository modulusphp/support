<?php

namespace Modulus\Support;

use Exception;

class DEPException
{
  /**
   * throw a new Exception
   *
   * @param string $message
   * @return void
   */
  public static function throwException(string $message) : void
  {
    throw new Exception($message);
  }
}