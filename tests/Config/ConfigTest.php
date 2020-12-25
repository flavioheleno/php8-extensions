<?php
declare(strict_types = 1);

use PHPUnit\Framework\TestCase;
use PHPExt\Config\Adapters\YamlAdapter;
use PHPExt\Config\Config;

final class ConfigTest extends TestCase {
  private $config;

  public function setUp() : void {
    $fixture = __DIR__ . '/../../tests/fixtures/config.yml';

    $adapter = new YamlAdapter($fixture);
    $this->config = new Config($adapter);
  }

  public function tearDown() : void {
    $this->config = null;
  }

  public function testConfigHasAllRequiredSections() {
    $expected = ['osMatrix', 'phpVerMatrix', 'osSpecs', 'extList'];

    foreach ($expected as $configKey) {
      $this->assertIsArray($this->config->{$configKey});
    }
  }
}