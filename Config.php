<?php

namespace Modulus\Support;

use Modulus\Support\DEPConfig;

class Config
{
  /**
   * $all
   *
   * @var array
   */
  public static $all;

  /**
   * Temporary config
   *
   * @var array
   */
  public static $temp = [];

  /**
   * Check if setting exists
   *
   * @param string $key
   * @return bool
   */
  public static function has(string $key) : bool
  {
    $expect = explode('.', $key);
    $config = Config::all();

    foreach($expect as $setting) {
      if (!isset($config[$setting])) return false;

      $config = $config[$setting];
    }

    return true;
  }

  /**
   * Get config
   *
   * @param  string $config
   * @return mixed  $service
   */
  public static function get(string $config)
  {
    if (isset(self::$temp[$config])) return self::$temp[$config];

    $conf = explode('.', $config);
    $path = DEPConfig::$appdir . 'config' . DIRECTORY_SEPARATOR . $conf[0] . '.php';

    if (!file_exists($path)) return null;

    $service = require $path;

    unset($conf[0]);

    foreach($conf as $setting) {
      if (isset($service[$setting])) {
        $service = $service[$setting];
      } else {
        return null;
      }
    }

    return $service;
  }

  /**
   * Set temporary config
   *
   * @param string $name
   * @param mixed $value
   * @return bool
   */
  public static function set(string $name, $value) : bool
  {
    if (isset(self::$temp[$name])) return false;

    return is_array(self::$temp = array_merge(self::$temp, [$name => $value]));
  }

  /**
   * Forget temporary config
   *
   * @param string $config
   * @return bool
   */
  public static function forget(string $config) : bool
  {
    if (array_key_exists($config, self::$temp)) {
      unset(self::$temp[$config]);

      return true;
    }

    return false;
  }

  /**
   * Get config files
   *
   * @param array $appConfig
   * @return array $appConfig
   */
  public static function all(array $appConfig = []) : array
  {
    if (Self::$all != null) return Self::$all;

    $configs = DEPConfig::$appdir . 'config' . DIRECTORY_SEPARATOR . '*.php';

    foreach (\glob($configs) as $config) {
      $service = require $config;

      if (is_array($service)) {
        $path = basename($config);
        $name = \substr($path, 0, strlen($path) - 4);
        $appConfig = array_merge($appConfig, [$name => $service]);
      }
    }

    Self::$all = $appConfig;

    return $appConfig;
  }
}
