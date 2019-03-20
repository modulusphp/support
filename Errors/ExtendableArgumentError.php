<?php

namespace Modulus\Support\Errors;

use Error;

class ExtendableArgumentError extends Error
{
  /**
   * __construct
   *
   * @param Error $error
   * @param bool $isStatic
   * @return mixed
   */
  public function __construct(Error $error, string $class, string $method, bool $isStatic = false)
  {
    if (starts_with($error->getMessage(), 'Argument')) {

      $chars    = explode(' ', $error->getMessage());

      $argument = (int)$chars[1];

      $srcClass = $chars[4];

      if ($argument == 1 && $isStatic == false) {

        $this->message = $error->getMessage();
        $this->code    = $error->getCode();
        $this->file    = $error->getFile();
        $this->line    = $error->getLine();

      } else {

        $args = debug_backtrace()[1];

        foreach ($args as $key => $value) {
          $this->{$key} = $value;
        }

        $newArgument = (!$isStatic ? $argument - 1 : $argument);

        /**
         * Update the error message
         */
        $message = str_replace("{$srcClass}", "{$class}::{$method}()", $error->getMessage());
        $this->message = str_replace("Argument {$argument} passed", "Argument {$newArgument} passed", $message);

      }

      return;

    } else if (starts_with($error->getMessage(), 'Too few arguments to function')) {

      $chars    = explode(' ', $error->getMessage());

      $passed   = (int)$chars[6];

      $expected = (int)$chars[10];

      $srcClass = $chars[5];

      // dd($chars);

      $args = debug_backtrace()[1];

      foreach ($args as $key => $value) {
        $this->{$key} = $value;
      }

      $newPassed   = (!$isStatic ? $passed - 1 : $passed);

      $newExpected = (!$isStatic ? $expected - 1 : $expected);

      /**
       * Update the error message
       */
      $message = str_replace(", {$passed} passed", ", {$newPassed} passed", $error->getMessage());
      $message = str_replace("{$srcClass}", "{$class}::{$method}()", $message);
      $this->message = str_replace("exactly {$expected} expected", "exactly {$newExpected} expected", $message);

      return;

    }

    /**
     * Throw the initial error
     */
    throw $error;
  }
}
