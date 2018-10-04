<?php

namespace Modulus\Support;

use Modulus\Support\{DEPConfig,
                        DEPException};

class Arr
{
  /**
   * Get mix file
   *
   * @param string $path
   * @param string $manifestDirectory
   * @return string $manifest[$path]
   */
  public static function mix(string $path, string $manifestDirectory) : string
  {
    static $manifest;

    if ($manifestDirectory && strpos($manifestDirectory, DIRECTORY_SEPARATOR) !== 0) {
      $manifestDirectory = DIRECTORY_SEPARATOR . "{$manifestDirectory}";
    }
    if (!$manifest) {
      if (!file_exists($manifestPath = (DEPConfig::$appdir . 'public' . DIRECTORY_SEPARATOR . 'mix-manifest.json') )) {
        DEPException::throwException('The Mix manifest does not exist.');
      }
      $manifest = json_decode(file_get_contents($manifestPath), true);
    }

    if (strpos($path, DIRECTORY_SEPARATOR) !== 0) {
      $path = DIRECTORY_SEPARATOR . "{$path}";
    }

    if (! array_key_exists($path, $manifest)) {
      DEPException::throwException(
        "Unable to locate Mix file: $path. Please check your ".
        'webpack.mix.js output paths and try again.'
      );
    }

    return $manifest[$path];
  }

  /**
   * Get config
   *
   * @param  string $config
   * @return mixed  $service
   */
  public static function config(string $config)
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
   * Get value that's like ?
   *
   * @param array $array
   * @param mixed $value
   * @return void
   */
  public static function like(array $array, $value)
  {
    foreach($array as $row => $line) {
      if (str_contains($line, $value)) return $line;
    }
  }

  /**
   * Replace array key
   *
   * @param array $array
   * @param mixed $oldKey
   * @param mixed $newKey
   * @return array $newArray
   */
  public static function replace_key(array $array, $oldKey, $newKey) : array
  {
    $newArray = array();

    foreach ($array as $key => $value) {
      if ($key == $oldKey) {
        $newArray = array_merge($newArray, [$newKey => $value]);
      } else {
        $newArray = array_merge($newArray, [$key => $value]);
      }
    }

    return $newArray;
  }
}