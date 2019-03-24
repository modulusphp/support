<?php

namespace Modulus\Support\Exceptions;

use Exception;

class CannotAddPropertyException extends Exception
{
  /**
   * $message
   *
   * @var string
   */
  protected $message = 'Property has already been added';

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
