<?php
declare(strict_types = 1);

namespace PHPExt\Config\Adapters;

use PHPExt\Config\AdapterInterface;
use Symfony\Component\Yaml\Yaml;

class YamlAdapter implements AdapterInterface {
  private $configLocation = __DIR__ . '/../../../config.yml';

  public function __construct($configLocation = null) {
    $this->configLocation = $configLocation ?? $this->configLocation;
  }

  public function load() : array {
    return Yaml::parse(file_get_contents($this->configLocation));
  }
}