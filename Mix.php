<?php

namespace Modulus\Support;

use Modulus\Support\DEPConfig;
use Modulus\Support\Exceptions\MixException;

class Mix
{
  /**
   * Get mix file
   *
   * @param string $path
   * @param string $manifestDirectory
   * @return string $manifest[$path]
   */
  public static function make(string $path, string $manifestDirectory) : string
  {
    static $manifest;

    if ($manifestDirectory && strpos($manifestDirectory, DIRECTORY_SEPARATOR) !== 0) {
      $manifestDirectory = DIRECTORY_SEPARATOR . "{$manifestDirectory}";
    }
    if (!$manifest) {
      if (!file_exists($manifestPath = (DEPConfig::$appdir . 'public' . DIRECTORY_SEPARATOR . 'mix-manifest.json') )) {
        throw new MixException('The Mix manifest does not exist.');
      }
      $manifest = json_decode(file_get_contents($manifestPath), true);
    }

    if (strpos($path, DIRECTORY_SEPARATOR) !== 0) {
      $path = DIRECTORY_SEPARATOR . "{$path}";
    }

    if (! array_key_exists($path, $manifest)) {
      throw new MixException(
        "Unable to locate Mix file: $path. Please check your ".
        'webpack.mix.js output paths and try again.'
      );
    }

    return $manifest[$path];
  }
}
