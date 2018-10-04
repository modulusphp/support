<?php

namespace Modulus\Support;

use Exception;

class File
{
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
   * @param mixed $file
   * @return void
   */
  public function __construct(array $file, ?string $disc = null)
  {
    $this->info = $file;
    $this->disc = ($disc == null) ? 'public' : $disc;

    $this->storage = [
      'public' => config('app.dir') . 'public' . DIRECTORY_SEPARATOR . 'storage' . DIRECTORY_SEPARATOR,
      'private' =>  config('app.dir') . 'storage' . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR,
    ];
  }

  /**
   * Set default disc storage
   *
   * @param string $storage
   * @return void
   */
  public function disc(string $storage)
  {
    if (in_array(strtolower($storage), ['public', 'private'])) {
      $this->disc = strtolower($storage);
      return $this;
    }

    throw new Exception('Unknown file storage.');
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
      $this->storage[$this->disc] . $this->cleanPath($dest);
      if (!$this->__dir($path)) throw new Exception('Storage is unusable.');
      $file = $path . DIRECTORY_SEPARATOR . $this->info['name'];
    } else {
      $file = $this->storage[$this->disc] . $this->info['name'];
    }

    if (file_exists($file)) {
      return (object)[
        'status' => 'failed',
        'reason' => 'file already exists',
      ];
    }

    if (move_uploaded_file($this->info['tmp_name'], $file)) {
      return (object)[
        'status' => 'success',
        'path' => $file,
      ];
    }
    else {
      return (object)[
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
