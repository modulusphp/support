<?php

namespace Modulus\Support;

use Modulus\Support\DEPConfig;

class Arr
{
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
