<?php

namespace Modulus\Support;

use Modulus\Support\Extendable;

class Filesystem
{
  use Extendable;

  /**
   * Filesystem::PUBLIC
   */
  const PUBLIC = 'public';

  /**
   * Filesysytem::PRIVATE
   */
  const PRIVATE = 'private';

  /**
   * Require the given file once.
   *
   * @param  string  $file
   * @return mixed
   */
  public static function requireOnce($file)
  {
    require_once $file;
  }

  /**
   * Get the MD5 hash of the file at the given path.
   *
   * @param  string  $path
   * @return string
   */
  public static function hash($path)
  {
    return md5_file($path);
  }

  /**
   * Write the contents of a file.
   *
   * @param  string  $path
   * @param  string  $contents
   * @param  bool  $lock
   * @return int
   */
  public static function put($path, $contents, $lock = false)
  {
    return file_put_contents($path, $contents, $lock ? LOCK_EX : 0);
  }

  /**
   * Get file contents
   *
   * @param string $path
   * @return string
   */
  public static function get(string $path)
  {
    return file_get_contents($path);
  }

  /**
   * Append to a file.
   *
   * @param  string  $path
   * @param  string  $data
   * @return int
   */
  public static function append($path, $data)
  {
    return file_put_contents($path, $data, FILE_APPEND);
  }

  /**
   * Get or set UNIX mode of a file or directory.
   *
   * @param  string  $path
   * @param  int  $mode
   * @return mixed
   */
  public static function chmod($path, $mode = null)
  {
    if ($mode) {
      return chmod($path, $mode);
    }

    return substr(sprintf('%o', fileperms($path)), -4);
  }

  /**
   * Move a file to a new location.
   *
   * @param  string  $path
   * @param  string  $target
   * @return bool
   */
  public static function move($path, $target)
  {
    return rename($path, $target);
  }

  /**
   * Create a hard link to the target file or directory.
   *
   * @param  string  $target
   * @param  string  $link
   * @return void
   */
  public static function link($target, $link)
  {
    if (! windows_os()) {
      return symlink($target, $link);
    }

    $mode = is_dir($target) ? 'J' : 'H';

    exec("mklink /{$mode} \"{$link}\" \"{$target}\"");
  }

  /**
   * Extract the file name from a file path.
   *
   * @param  string  $path
   * @return string
   */
  public static function name($path)
  {
    return pathinfo($path, PATHINFO_FILENAME);
  }

  /**
   * Extract the trailing name component from a file path.
   *
   * @param  string  $path
   * @return string
   */
  public static function basename($path)
  {
    return pathinfo($path, PATHINFO_BASENAME);
  }

  /**
   * Extract the parent directory from a file path.
   *
   * @param  string  $path
   * @return string
   */
  public static function dirname($path)
  {
    return pathinfo($path, PATHINFO_DIRNAME);
  }

  /**
   * Extract the file extension from a file path.
   *
   * @param  string  $path
   * @return string
   */
  public static function extension($path)
  {
    return pathinfo($path, PATHINFO_EXTENSION);
  }

  /**
   * Get the file type of a given file.
   *
   * @param  string  $path
   * @return string
   */
  public static function type($path)
  {
    return filetype($path);
  }

  /**
   * Get the mime-type of a given file.
   *
   * @param  string  $path
   * @return string|false
   */
  public static function mimeType($path)
  {
    return finfo_file(finfo_open(FILEINFO_MIME_TYPE), $path);
  }

  /**
   * Get the file size of a given file.
   *
   * @param  string  $path
   * @return int
   */
  public static function size($path)
  {
    return filesize($path);
  }

  /**
   * Get the file's last modification time.
   *
   * @param  string  $path
   * @return int
   */
  public static function lastModified($path)
  {
      return filemtime($path);
  }

  /**
   * Determine if the given path is a directory.
   *
   * @param  string  $directory
   * @return bool
   */
  public static function isDirectory($directory)
  {
    return is_dir($directory);
  }

  /**
   * Determine if the given path is readable.
   *
   * @param  string  $path
   * @return bool
   */
  public static function isReadable($path)
  {
    return is_readable($path);
  }

  /**
   * Determine if the given path is writable.
   *
   * @param  string  $path
   * @return bool
   */
  public static function isWritable($path)
  {
    return is_writable($path);
  }

  /**
   * Determine if the given path is a file.
   *
   * @param  string  $file
   * @return bool
   */
  public static function isFile($file)
  {
    return is_file($file);
  }

  /**
   * Find path names matching a given pattern.
   *
   * @param  string  $pattern
   * @param  int     $flags
   * @return array
   */
  public static function glob($pattern, $flags = 0)
  {
    return glob($pattern, $flags);
  }

  /**
   * Create a directory.
   *
   * @param  string  $path
   * @param  int     $mode
   * @param  bool    $recursive
   * @param  bool    $force
   * @return bool
   */
  public static function makeDirectory($path, $mode = 0755, $recursive = false, $force = false)
  {
    if ($force) {
      return @mkdir($path, $mode, $recursive);
    }

    return mkdir($path, $mode, $recursive);
  }

  /**
   * Copy file or folder
   *
   * @param string $source
   * @param string $destination
   * @return void
   */
  public static function copy(string $source, string $destination)
  {
    if (is_dir($source)) {
      @mkdir($destination);

      $directory = dir($source);

      while (false !== ($readdirectory = $directory->read())) {
        if ($readdirectory == '.' || $readdirectory == '..') {
          continue;
        }

        $PathDir = $source . '/' . $readdirectory;

        if (is_dir($PathDir)) {
          Filesystem::copy($PathDir, $destination . '/' . $readdirectory);
          continue;
        }
        copy($PathDir, $destination . '/' . $readdirectory);
      }

      $directory->close();
    } else {
      copy($source, $destination);
    }
  }

  /**
   * Delete file or folder
   *
   * @param mixed $path
   * @return bool
   */
  public static function delete($path)
  {
    if (is_file($path)) {
      return unlink($path);
    }

    $files = array_diff(scandir($path), array('.', '..'));

    foreach ($files as $file) {
      (is_dir("$path/$file")) ? Filesystem::delete("$path/$file") : unlink("$path/$file");
    }

    return rmdir($path);
  }
}
