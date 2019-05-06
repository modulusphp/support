<?php

namespace Modulus\Support;

use Error;
use Closure;
use Modulus\Support\Errors\UndefinedMethodError;
use Modulus\Support\Errors\ExtendableArgumentError;
use Modulus\Support\Exceptions\CannotAddMethodException;
use Modulus\Support\Exceptions\CannotCallMethodException;
use Modulus\Support\Exceptions\CannotAddPropertyException;
use Modulus\Support\Exceptions\UndefinedPropertyErrorException;

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
   * A list of custom properties
   *
   * @var array $properties
   */
  public static $properties = [];

  /**
   * Add custom function
   *
   * @param string $method The name of the method
   * @param Closure $closure The callback that should be executed
   * @throws CannotAddMethodException|CannotCallMethodException
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
   * @param string $method The name of the method
   * @param Closure $closure The callback that should be executed
   * @throws CannotAddMethodException|CannotCallMethodException
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

  /**
   * Add custom property
   *
   * @param string $property The name of the property
   * @param Closure $closure The callback that should be executed
   * @throws CannotAddPropertyException|CannotCallMethodException
   * @return mixed
   */
  public static function prop(string $property, Closure $closure)
  {
    $trace = debug_backtrace()[1];

    if (
      $trace['class'] == 'Modulus\Framework\Upstart\Prototype' &&
      $trace['function'] == 'prop' &&
      count($trace['args']) == 3
    ) {
      if (!array_key_exists($property, Self::$properties)) {
        return Self::$properties[$property] = [$property => $closure];
      }

      throw new CannotAddPropertyException;
    }

    throw new CannotCallMethodException('Call to ' . self::class . '::prop() is not allowed');
  }

  /**
   * Call custom function
   *
   * @param string $method The name of the method
   * @param array $args
   * @throws ExtendableArgumentError|UndefinedMethodError
   * @return mixed
   */
  public function __call(string $method, array $args)
  {
    if (array_key_exists($method, Self::$functions)) {
      try {
        return call_user_func_array(Self::$functions[$method][$method], array_merge([$this], $args));
      } catch (Error $e) {
        throw new ExtendableArgumentError($e, self::class, $method);
      }
    }

    /**
     * Method does not exist
     */
    throw new UndefinedMethodError('Call to undefined method ' . __CLASS__ . "::{$method}()");
  }

  /**
   * Call custom static function
   *
   * @param string $method The name of the method
   * @param array $args
   * @throws ExtendableArgumentError|UndefinedMethodError
   * @return mixed
   */
  public static function __callStatic(string $method, array $args)
  {
    if (array_key_exists($method, Self::$staticFunctions)) {
      try {
        return call_user_func_array(Self::$staticFunctions[$method][$method], $args);
      } catch (Error $e) {
        throw new ExtendableArgumentError($e, self::class, $method, true);
      }
    }

    /**
     * Static method does not exist
     */
    throw new UndefinedMethodError('Call to undefined method ' . __CLASS__ . "::{$method}()");
  }

  /**
   * Call custom property
   *
   * @param string $property The name of the property
   * @param array $args
   * @throws ExtendableArgumentError|UndefinedPropertyErrorException
   * @return mixed
   */
  public function __get(string $property)
  {
    if (array_key_exists($property, Self::$properties)) {
      try {
        return call_user_func_array(Self::$properties[$property][$property], [$this]);
      } catch (Error $e) {
        throw new ExtendableArgumentError($e, self::class, $property);
      }
    }

    /**
     * Property does not exist
     */
    throw new UndefinedPropertyErrorException('Undefined property ' . __CLASS__ . '::$' . $property);
  }
}
