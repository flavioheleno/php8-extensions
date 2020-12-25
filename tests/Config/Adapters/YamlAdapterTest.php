<?php
declare(strict_types = 1);

use PHPUnit\Framework\TestCase;
use PHPExt\Config\Adapters\YamlAdapter;

final class YamlAdapterTest extends TestCase {
  private $config;

  public function setUp() : void {
    $fixture = __DIR__ . '/../../../tests/fixtures/config.yml';

    $adapter = new YamlAdapter($fixture);
    $this->config = $adapter->load();
  }

  public function tearDown() : void {
    $this->config = null;
  }

  public function testConfigHasAllRequiredSections() {
    $keys = array_keys($this->config);
    $expected = ['osMatrix', 'phpVerMatrix', 'osSpecs', 'extList'];

    $this->assertEquals($expected, $keys);
  }
}