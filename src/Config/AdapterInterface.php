<?php
declare(strict_types = 1);

namespace PHPExt\Config;

interface AdapterInterface {
  public function load() : array;
}
