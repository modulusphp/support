<?php

namespace Modulus\Support\Exceptions;

use Exception;

class UndefinedPropertyErrorException extends Exception
{
  /**
   * __construct
   *
   * @return void
   */
  public function __construct(string $message)
  {
    $args = debug_backtrace()[1];

    foreach ($args as $key => $value) {
      $this->{$key} = $value;
    }

    $this->message = $message;
  }
}
