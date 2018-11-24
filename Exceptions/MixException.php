<?php

namespace Modulus\Support\Exceptions;

use Exception;

class MixException extends Exception
{
  /**
   * __construct
   *
   * @param string $message
   * @return void
   */
  public function __construct(string $message)
  {
    $this->message = $message;

    $position = count(debug_backtrace()) == 16 ? 9 : 8;
    $args     = debug_backtrace()[$position];

    foreach ($args as $key => $value) {
      $this->{$key} = $value;
    }
  }
}
