<?php

namespace Modulus\Support\Exceptions;

use Exception;

class UnknownStorageException extends Exception
{
  /**
   * $message
   *
   * @var string
   */
  protected $message = "File storage does not exists";

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
