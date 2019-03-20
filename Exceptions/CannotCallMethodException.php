<?php

namespace Modulus\Support\Exceptions;

use Exception;

class CannotCallMethodException extends Exception
{
  /**
   * __construct
   *
   * @param string $message
   * @return void
   */
  public function __construct(string $message)
  {
    $args = debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT, 3)[1];

    foreach ($args as $key => $value) {
      $this->{$key} = $value;
    }

    $this->message = $message;
  }
}
