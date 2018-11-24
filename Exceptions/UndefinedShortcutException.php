<?php

namespace Modulus\Support\Exceptions;

use Exception;

class UndefinedShortcutException extends Exception
{
  /**
   * __construct
   *
   * @param string $message
   * @param array $userArgs
   * @return void
   */
  public function __construct(string $message, array $userArgs = [])
  {
    $args = debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT, 3)[2];

    foreach ($args as $key => $value) {
      $this->{$key} = $value;
    }

    $this->message = $message;
  }
}
