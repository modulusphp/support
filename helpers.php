<?php

use Carbon\Carbon;
use Modulus\Http\Rest;
use Modulus\Http\Route;
use Modulus\Support\Arr;
use Modulus\Support\Mix;
use Modulus\Support\Obj;
use Modulus\Http\Session;
use Modulus\Utility\View;
use Modulus\Http\Redirect;
use Modulus\Security\Auth;
use Modulus\Support\Config;
use Modulus\Utility\Events;
use Modulus\Hibernate\Cache;
use Modulus\Utility\Command;
use Modulus\Utility\Process;
use Modulus\Support\Shortcut;
use Modulus\Utility\Variable;
use Modulus\Http\UrlGenerator;
use Modulus\Hibernate\Queue\Job;
use Modulus\Utility\Notification;
use AtlantisPHP\Telemonlog\Output;
use Modulus\Hibernate\Queue\Dispatcher;
use Modulus\Hibernate\Queue\ShouldQueue;

if (!function_exists('command')) {
  /**
   * Run command
   *
   * @param string $args
   * @return SymfonyProcess $process
   */
  function command(string $command) {
    return Command::run($command);
  }
}

if (!function_exists('process')) {
  /**
   * Execute process
   *
   * @return SymfonyProcess
   */
  function process($command) {
    return Process::run($command);
  }
}

if (!function_exists('old')) {
  /**
   * Get old value or fallback
   *
   * @param mixed $value
   * @param mixed $fallback
   * @return void
   */
  function old($value, $fallback = '') {
    return Obj::old($value, $fallback);
  }
}

if (!function_exists('dump')) {
  /**
   * Dump
   *
   * @return void
   */
  function dump() {
    return Obj::d(func_get_args());
  }
}

if (!function_exists('d')) {
  /**
   * Dump
   *
   * @return void
   */
  function d() {
    return Obj::d(func_get_args());
  }
}

if (!function_exists('dd')) {
  /**
   * Die and dump
   *
   * @return void
   */
  function dd() {
    return Obj::dd(func_get_args());
  }
}

if (!function_exists('config')) {
  /**
   * Get config
   *
   * @param  string $config
   * @return mixed  $service
   */
  function config(string $config) {
    return Config::get($config);
  }
}

if (!function_exists('view')) {
  /**
   * Make a view
   *
   * @param  string $path
   * @param  array  $data
   * @return void
   */
  function view($view, $data = []) {
    return View::make($view, $data);
  }
}

if (!function_exists('response')) {
  /**
   * Build a response
   *
   * @param  string  $message
   * @param  integer $code
   * @param  array   $headers
   * @return Rest
   */
  function response(string $message = null, $code = null, array $headers = []) {
    return Rest::response($message, $code, $headers);
  }
}

if (!function_exists('redirect')) {
  /**
   * Ruturns a redirect object
   *
   * @return Redirect
   */
  function redirect(?string $url = null) {
    return (new Redirect($url));
  }
}

if (!function_exists('route')) {
  /**
   * Generate url from route
   *
   * @param string $name
   * @param null|array $parameters
   * @return string $url
   */
  function route(string $name, ?array $parameters = []) {
    return Route::url($name, $parameters);
  }
}

if (!function_exists('mix')) {
  /**
   * Get mix file
   *
   * @param string $path
   * @param string $manifestDirectory
   * @return string $manifest[$path]
   */
  function mix($path, $manifestDirectory = '') {
    return Mix::make($path, $manifestDirectory);
  }
}

if (!function_exists('startphp')) {
  /**
   * Require once
   *
   * @param string $path
   * @return void
   */
  function startphp($path) {
    return Obj::startphp($path);
  }
}

if (!function_exists('url')) {
  /**
   * Generate url
   *
   * @param string $path
   * @param mixed ?bool
   * @return string
   */
  function url($path, bool $uri = false) {
    return UrlGenerator::get($path, $uri);
  }
}

if (!function_exists('auth')) {
  /**
   * Returns a Auth object
   *
   * @return Auth
   */
  function auth() {
    return new Auth;
  }
}

if (!function_exists('is_base64')) {
  /**
   * Check if string is base64 or not
   *
   * @param string $string
   * @return bool
   */
  function is_base64(string $string) {
    return Obj::is_base64($string);
  }
}

if (!function_exists('then')) {
  /**
   * If ? then
   *
   * @param bool $if
   * @param mixed $then
   * @return void
   */
  function then(bool $if, $then) {
    return Obj::then($if, $then);
  }
}

if (!function_exists('array_replace_key')) {
  /**
   * Replace array key
   *
   * @param array $array
   * @param mixed $oldKey
   * @param mixed $newKey
   * @return array $newArray
   */
  function array_replace_key(array $array, $oldKey, $newKey) {
    return Arr::replace_key($array, $oldKey, $newKey);
  }
}

if (!function_exists('array_value_like')) {
  /**
   * Get value that's like ?
   *
   * @param array $array
   * @param mixed $value
   * @return mixed
   */
  function array_value_like($array, $value) {
    return Arr::like($array, $value);
  }
}

if (!function_exists('cancel')) {
  /**
   * Cancel application cycle
   *
   * @param mixed $message
   * @return void
   */
  function cancel($message = null) {
    return Obj::cancel($message);
  }
}

if (!function_exists('database_path')) {
  /**
   * database_path
   *
   * @param string $file
   * @return string
   */
  function database_path($file = null) {
    return Obj::database_path($file);
  }
}

if (!function_exists('storage_path')) {
  /**
   * storage_path
   *
   * @param string $file
   * @return string
   */
  function storage_path($file = null) {
    return Obj::storage_path($file);
  }
}

if (!function_exists('public_path')) {
  /**
   * public_path
   *
   * @param string $file
   * @return string
   */
  function public_path($file = null) {
    return Obj::public_path($file);
  }
}

if (!function_exists('base_path')) {
  /**
   * bsae_path
   *
   * @param string $file
   * @return string
   */
  function base_path($file = null) {
    return Obj::base_path($file);
  }
}

if (!function_exists('get')) {
  /**
   * Get variable
   *
   * @param string $name
   * @return mixed
   */
  function get($name) {
    return Variable::get($name);
  }
}

if (!function_exists('has')) {
  /**
   * Check if variables exists
   *
   * @param string $name
   * @param null|string $syntax
   * @return bool
   */
  function has($name, ?string $syntax = null) {
    return Variable::has($name, $syntax);
  }
}

if (!function_exists('listen')) {
  /**
   * listen to an event
   *
   * @param  string $name
   * @param  mixed  $callback
   * @return void
   */
  function listen($name, $callback) {
    return Events::listen($name, $callback);
  }
}

if (!function_exists('trigger')) {
  /**
   * trigger an event
   *
   * @param string     $name
   * @param null|array $args
   * @return void
   */
  function trigger($name, ?array $data = []) {
    return Events::trigger($name, $data);
  }
}

if (!function_exists('emergency')) {
  /**
   * emergency
   *
   * @param string $message
   * @param array $array
   * @return void
   */
  function emergency($message, $data = []) {
    return Output::emergency($message, $data);
  }
}

if (!function_exists('alert')) {
  /**
   * alert
   *
   * @param string $message
   * @param array $array
   * @return void
   */
  function alert($message, $data = []) {
    return Output::alert($message, $data);
  }
}

if (!function_exists('critical')) {
  /**
   * critical
   *
   * @param string $message
   * @param array $array
   * @return void
   */
  function critical($message, $data = []) {
    return Output::critical($message, $data);
  }
}

if (!function_exists('info')) {
  /**
   * info
   *
   * @param string $message
   * @param array $array
   * @return void
   */
  function info($message, $data = []) {
    return Output::info($message, $data);
  }
}

if (!function_exists('cache')) {
  /**
   * Get, set value or return cache object
   *
   * @return mixed
   */
  function cache() {
    $args = func_get_args();

    if (count($args) == 1) {
      return Cache::get($args[0]);
    } else if (count($args) == 2) {
      return Cache::forever($args[0], $args[1]);
    } else if (count($args) == 3) {
      return Cache::set($args[0], $args[1], $args[2]);
    }

    return new Cache();
  }
}

if (!function_exists('___')) {
  /**
   * Call helper function
   *
   * @param string $function
   * @param mixed $parameters
   */
  function ___(string $function) {
    $args = func_get_args();
    unset($args[0]);
    return Shortcut::run($function, $args);
  }
}

if (!function_exists('fn')) {
  /**
   * Call helper function
   *
   * @param string $function
   * @param mixed $parameters
   * @deprecated 2.0
   */
  function fn(string $function) {
    $args = func_get_args();
    unset($args[0]);
    return Shortcut::run($function, $args);
  }
}

if (!function_exists('func')) {
  /**
   * Call helper function
   *
   * @param string $function
   * @param mixed $parameters
   */
  function func(string $function) {
    $args = func_get_args();
    unset($args[0]);
    return Shortcut::run($function, $args);
  }
}

if (!function_exists('notify')) {
  /**
   * Sends notification
   *
   * @param Notifcation $notification
   * @return array
   */
  function notify(Notification $notification) {
    return (new Notification($notification))->now();
  }
}

if (!function_exists('session')) {
  /**
   * Return a session class
   *
   * @param mixed $key
   * @param mixed $value
   * @return
   */
  function session($key = null, $value = null) {
    if ($key == null) return new Session;
    return Session::key($key, $value);
  }
}

if (!function_exists('flash')) {
  /**
   * Create a flash message
   *
   * @param string $key
   * @param mixed $value
   * @return
   */
  function flash(string $key, $value = null) {
    return Session::flash($key, $value);
  }
}

if (!function_exists('dispatch')) {
  /**
   * Pushes a new job onto the Hibernate Queue
   *
   * @param ShouldQueue $queue The dispatchable job
   * @param Carbon|null $delay Set the desired delay for the job
   * @return string
   */
  function dispatch(ShouldQueue $queue, ?Carbon $delay = null) {
    return Dispatcher::now($queue, $delay);
  }
}
