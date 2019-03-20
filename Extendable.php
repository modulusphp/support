<?php

namespace Modulus\Support;

use Closure;
use Modulus\Support\Exceptions\CannotAddMethodException;
use Modulus\Support\Exceptions\CannotCallMethodException;

trait Extendable
{
  /**
   * A list of custom functions
   *
   * @var array $functions
   */
  public static $functions = [];

  /**
   * A list of custom static functions
   *
   * @var array $staticFunctions
   */
  public static $staticFunctions = [];

  /**
   * Add custom function
   *
   * @param string $method
   * @param Closure $closure
   * @return mixed
   */
  public static function bind(string $method, Closure $closure)
  {
    $trace = debug_backtrace()[1];

    if (
      $trace['class'] == 'Modulus\Framework\Upstart\Prototype' &&
      $trace['function'] == 'bind' &&
      count($trace['args']) == 3
    ) {
      if (!array_key_exists($method, Self::$functions)) {
        return Self::$functions[$method] = [$method => $closure];
      }

      throw new CannotAddMethodException;
    }

    throw new CannotCallMethodException('Call to ' . self::class . '::bind() is not allowed');
  }

  /**
   * Add custom static function
   *
   * @param string $method
   * @param Closure $closure
   * @return mixed
   */
  public static function static(string $method, Closure $closure)
  {
    $trace = debug_backtrace()[1];

    if (
      $trace['class'] == 'Modulus\Framework\Upstart\Prototype' &&
      $trace['function'] == 'static' &&
      count($trace['args']) == 3
    ) {
      if (!array_key_exists($method, Self::$staticFunctions)) {
        return Self::$staticFunctions[$method] = [$method => $closure];
      }

      throw new CannotAddMethodException;
    }

    throw new CannotCallMethodException('Call to ' . self::class . '::static() is not allowed');
  }
}
