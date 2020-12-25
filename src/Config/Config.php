<?php
declare(strict_types = 1);

namespace PHPExt\Config;

use PHPExt\Config\AdapterInterface;

class Config {
  private $adapter;
  private $config;

  public function __construct(AdapterInterface $adapter) {
    $this->adapter = $adapter;
    $this->config = $this->adapter->load();
  }

  public function __get($propertie) {
    if (in_array($propertie, array_keys($this->config))) {
      return $this->config[$propertie];
    }
  }
}
