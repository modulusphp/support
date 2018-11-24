<?php

namespace Modulus\Support\Exceptions;

use Exception;

class StorageUnusableException extends Exception
{
  /**
   * $message
   *
   * @var string
   */
  protected $message = "Storage is unusable. It could be a permission issue";

  /**
   * __construct
   *
   * @return void
   */
  public function __construct()
  {
    $args = debug_backtrace()[1];

    foreach ($args as $key => $value) {
      $this->{$key} = $value;
    }
  }
}
