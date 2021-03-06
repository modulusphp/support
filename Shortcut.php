<?php

namespace Modulus\Support;

use Exception;
use ReflectionClass;
use Modulus\Support\Exceptions\UndefinedShortcutException;
use Modulus\Support\Exceptions\ShortcutsClassNotFoundException;

class Shortcut
{
  /**
   * Run helper
   *
   * @param string $function
   * @param array $args
   * @return mixed
   */
  public static function run(string $function, array $args = [])
  {
    if (!class_exists(\App\Utils\Shortcuts::class)) {
      throw new ShortcutsClassNotFoundException;
    }

    $shortcuts = new \App\Utils\Shortcuts;

    $props = (new ReflectionClass($shortcuts))->getProperties();

    foreach($props as $prop) {
      if ($prop->name == 'functions' && array_key_exists($function, $shortcuts::$functions)) {
        return call_user_func_array([$shortcuts, $function], $args);
      }
    }

    if (method_exists($shortcuts, $function)) {
      return call_user_func_array([$shortcuts, $function], $args);
    }

    throw new UndefinedShortcutException("Shortcut '{$function}()' does not exist", $args);
  }
}
