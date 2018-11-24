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
    $conf = explode('.', $config);
    $path = DEPConfig::$appdir . 'config' . DIRECTORY_SEPARATOR . $conf[0] . '.php';

    $service = require $path;
    unset($conf[0]);

    foreach($conf as $setting) {
      $service = $service[$setting];
    }

    return $service;
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
