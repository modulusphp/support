<?php

use Modulus\Http\Rest;
use Modulus\Support\Arr;
use Modulus\Support\Obj;
use Modulus\Security\Auth;
use Modulus\Utility\View;
use Modulus\Utility\Command;
use Modulus\Utility\Process;
use Modulus\Utility\Notification;

if (!function_exists('command')) {
  function command(string $command) {
    return Command::run($command);
  }
}

if (!function_exists('process')) {
  function process($command) {
    return Process::run($command);
  }
}

if (!function_exists('old')) {
  function old($value) {
    return Obj::old($value);
  }
}

if (!function_exists('d')) {
  function d() {
    return Obj::d(func_get_args());
  }
}

if (!function_exists('dd')) {
  function dd() {
    return Obj::dd(func_get_args());
  }
}

if (!function_exists('config')) {
  function config(string $config) {
    return Arr::config($config);
  }
}

if (!function_exists('view')) {
  function view($view, $data = []) {
    View::make($view, $data);
  }
}

if (!function_exists('response')) {
  function response(string $message = null, $code = null, array $headers = []) {
    return Rest::response($message, $code, $headers);
  }
}

if (!function_exists('mix')) {
  function mix($path, $manifestDirectory = '') {
    return Arr::mix($path, $manifestDirectory);
  }
}

if (!function_exists('startphp')) {
  function startphp($path) {
    return Obj::startphp($path);
  }
}

if (!function_exists('url')) {
  function url($path, bool $uri = false) {
    return Obj::url($path, $uri);
  }
}

if (!function_exists('auth')) {
  function auth() {
    return new Auth;
  }
}

if (!function_exists('is_base64')) {
  function is_base64(string $string) {
    return Obj::is_base64($string);
  }
}

if (!function_exists('then')) {
  function then(bool $if, $then) {
    return Obj::then($if, $then);
  }
}

if (!function_exists('array_replace_key')) {
  function array_replace_key(array $array, $oldKey, $newKey) {
    return Arr::replace_key($array, $oldKey, $newKey);
  }
}

if (!function_exists('array_value_like')) {
  function array_value_like($array, $value) {
    return Arr::like($array, $value);
  }
}

if (!function_exists('cancel')) {
  function cancel($message = null) {
    return Obj::cancel($message);
  }
}

if (!function_exists('storage_path')) {
  function storage_path($file = null, $disc = null) {
    return Obj::storage_path($file, $disc);
  }
}

if (!function_exists('public_path')) {
  function public_path($file = null) {
    return Obj::public_path($file);
  }
}