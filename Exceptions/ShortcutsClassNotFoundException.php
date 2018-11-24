<?php

namespace Modulus\Support\Exceptions;

use Exception;

class ShortcutsClassNotFoundException extends Exception
{
  /**
   * $message
   *
   * @var string
   */
  protected $message = "class '\App\Utils\Shortcuts' not found";

  /**
   * __construct
   *
   * @return void
   */
  public function __construct()
  {
    $args = debug_backtrace()[2];

    foreach ($args as $key => $value) {
      $this->{$key} = $value;
    }
  }
}
