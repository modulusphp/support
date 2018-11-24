<?php

namespace Modulus\Support;

use Modulus\Utility\Asset;
use Modulus\Utility\Variable;
use Modulus\Support\{DEPConfig,
                        DEPException};

class Obj
{
  /**
   * Get env
   *
   * @param mixed string
   * @param mixed $fallback
   * @return mixed
   */
  public static function env(string $value = null, $fallback = '')
  {
    $val = getenv($value);

    if ($val == null || $val == '') return $fallback;
    return $fallback;
  }

  /**
   * Get old value
   *
   * @param mixed $value
   * @return void
   */
  public static function old($value)
  {
    return Variable::has('form.old') ? (isset(Variable::get('form.old')[$value]) ? Variable::get('form.old')[$value] :'') : '';
  }

  /**
   * Dump
   *
   * @return void
   */
  public static function d()
  {
    $args = func_get_args();
    call_user_func_array('dump', $args);
  }

  /**
   * Die and dump
   *
   * @return void
   */
  public static function dd()
  {
    $args = func_get_args();
    call_user_func_array('dump', $args);
    die();
  }

  /**
   * Set url (don't use this)
   *
   * @param mixed $path
   * @return void
   */
  public static function url(string $path, $uri)
  {
    $path = substr($path, 0, 1) != '/' ? '/' . $path : $path;

    $root = isset($_SERVER["SCRIPT_NAME"]) ? $_SERVER['SCRIPT_NAME'] : '';
    $dir = pathinfo($root)['dirname'];

    if (isset($_SERVER['HTTP_HOST'])) {
      if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') {
        $http = 'https://';
      } else {
        $http = 'http://';
      }

      $url = $http . $_SERVER['HTTP_HOST'] . ($dir == '/' ? '' : $dir) . $path;
    } else {
      $url = ($uri ? config('app.url') : '') . $path;
    }

    if (isset($_SERVER['QUERY_STRING'])) {
      if (str_contains($url, '?')) $url .= '&' . $_SERVER['QUERY_STRING'];
      if (!str_contains($url, '?')) $url .= '?' . $_SERVER['QUERY_STRING'];
    }

    return $url;
  }

  /**
   * Check if string is base64 or not
   *
   * @param string $string
   * @return bool
   */
  public static function is_base64(string $string) : bool
  {
    if (base64_encode(base64_decode($string, true)) === $string) return true;
    return false;
  }

  /**
   * If ? then
   *
   * @param bool $if
   * @param mixed $then
   * @return void
   */
  public static function then(bool $if, $then)
  {
    if ($if == true) return $then;
  }

  /**
   * Require once
   *
   * @param string $path
   * @return void
   */
  public static function startphp(string $path)
  {
    if (substr($path, strlen($path) - 4, 4) == '.php') return require_once $path;
    return require_once $path . '.php';
  }

  /**
   * database_path
   *
   * @param string $file
   * @return void
   */
  public static function database_path(?string $file = null)
  {
    $file = (substr($file, 0, 1) == DIRECTORY_SEPARATOR ? substr($file, 1) : $file);
    return config('app.dir') . 'database' . DIRECTORY_SEPARATOR . $file;
  }

  /**
   * storage_path
   *
   * @param string $file
   * @return void
   */
  public static function storage_path(?string $file = null)
  {
    $file = (substr($file, 0, 1) == DIRECTORY_SEPARATOR ? substr($file, 1) : $file);
    return config('app.dir') . 'storage' . DIRECTORY_SEPARATOR . $file;
  }

  /**
   * public_path
   *
   * @param string $file
   * @return void
   */
  public static function public_path(?string $file = null)
  {
    $file = (substr($file, 0, 1) == DIRECTORY_SEPARATOR ? substr($file, 1) : $file);
    return config('app.dir') . 'storage' . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . $file;
  }

   /**
   * bsae_path
   *
   * @param string $file
   * @return void
   */
  public static function base_path(?string $file = null)
  {
    $file = (substr($file, 0, 1) == DIRECTORY_SEPARATOR ? substr($file, 1) : $file);
    return config('app.dir') . $file;
  }

  /**
   * Cancel application cycle
   *
   * @param mixed $message
   * @return void
   */
  public static function cancel($message = null)
  {
    die($message != null ? $message : '');
  }
}
