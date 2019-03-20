<?php

namespace Modulus\Support;

use Exception;
use Modulus\Support\Config;
use Modulus\Support\Extendable;
use Modulus\Support\Exceptions\UnknownStorageException;
use Modulus\Support\Exceptions\StorageUnusableException;

class File
{
  use Extendable;

  /**
   * $info
   *
   * @var string
   */
  protected $info;

  /**
   * $disc
   *
   * @var string
   */
  protected $disc;

  /**
   * $storage
   *
   * @var array
   */
  protected $storage;

  /**
   * __construct
   *
   * @param array $file
   * @param string|null $storage
   * @return void
   */
  public function __construct(array $file, ?string $storage = null)
  {
    $this->info    = $file;
    $this->storage = Config::get('filesystems.disks');

    if ($storage != null) {
      if (isset($this->storage[$storage])) {
        $this->disc = $storage;
        return;
      }

      throw new UnknownStorageException;
    }

    $this->disc = 'public';

  }

  /**
   * Set default disc storage
   *
   * @param string $storage
   * @return void
   */
  public function disc(string $storage)
  {
    if (isset($this->storage[$storage])) {
      $this->disc = strtolower($storage);
      return $this;
    }

    throw new UnknownStorageException;
  }

  /**
   * Create a new instance of the file class
   *
   * @param array $file
   * @return void
   */
  public static function make(array $file, ?string $disc = 'public')
  {
    return new File($file, $disc);
  }

  /**
   * Get name
   *
   * @return string
   */
  public function name(?string $name = null, ?bool $extension = false)
  {
    if ($name !== null) {
      $this->info['name'] = $name . ($extension ? '.' . pathinfo($this->info['name'], PATHINFO_EXTENSION) : '');
      return $this;
    }

    return $this->info['name'];
  }

  /**
   * Move file
   *
   * @param string $dest
   * @return void
   */
  public function move(?string $dest = null)
  {
    if ($dest != null || $dest != '') {
      $path = $this->storage[$this->disc]['root'] . DIRECTORY_SEPARATOR . $this->cleanPath($dest);
      if (!$this->__dir($path)) throw new StorageUnusableException;
      $file = $path . DIRECTORY_SEPARATOR . $this->info['name'];
    } else {
      $file = $this->storage[$this->disc]['root'] . DIRECTORY_SEPARATOR . $this->info['name'];
    }

    if (file_exists($file)) {
      return [
        'status' => 'failed',
        'reason' => 'file already exists',
      ];
    }

    if (move_uploaded_file($this->info['tmp_name'], $file)) {

      /**
       * For some weird reason, sometimes files don't get
       * moved. So let's run this again and hope this file
       * will get moved
       */
      if (!file_exists($file)) {
        move_uploaded_file($this->info['tmp_name'], $file);
      }

      return [
        'status' => 'success',
        'path' => $file,
      ];
    }
    else {
      return [
        'status' => 'failed',
        'reason' => 'something went wrong',
      ];
    }
  }

  /**
   * cleanPath
   *
   * @param string $dest
   * @return void
   */
  private function cleanPath(string $dest)
  {
    $dest = substr($dest, 0, 1) == DIRECTORY_SEPARATOR ? substr($dest, 1) : $dest;
    return $dest[strlen($dest) - 1] == '/' ? substr($dest, 0, strlen($dest) - 1) : $dest;
  }

  /**
   * __dir
   *
   * @param string $dir
   * @return void
   */
  private function __dir(string $dir) : bool
  {
    if (!is_dir($dir)) {
      mkdir($dir, 0777, true);
    }

    if (is_dir($dir)) return true;

    return false;
  }
}
