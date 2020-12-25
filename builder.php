<?php
declare(strict_types = 1);

require_once __DIR__ . '/vendor/autoload.php';

use PHPExt\Config\Adapters\YamlAdapter;
use PHPExt\Config\Config;

$config = new Config(new YamlAdapter);

$buildPath = '/tmp';

/************************************/
/**                                **/
/**  DO NOT CHANGE THE CODE BELOW  **/
/**  UNLESS YOU ARE SURE ABOUT     **/
/**  WHAT YOU ARE DOING. :-)       **/
/**                                **/
/************************************/

$fs = new League\Flysystem\Filesystem(
  new League\Flysystem\Local\LocalFilesystemAdapter($buildPath)
);

if (is_dir("{$buildPath}/php8-extensions")) {
  $fs->deleteDirectory('./php8-extensions');
}

$fs->createDirectory('./php8-extensions');
// build matrix
foreach ($config->phpVerMatrix as $phpVersion) {
  foreach ($config->osMatrix as $osName) {
    try {
      echo 'Creating build files for PHP v', $phpVersion, '@', $osName, PHP_EOL;
      $dockerTag = "{$phpVersion}-{$osName}";
      // replace docker tag non alphanumeric chars with a dash
      $baseName = preg_replace(
        '/[^a-zA-Z0-9]/',
        '-',
        $dockerTag
      );

      $fs->createDirectory("./php8-extensions/{$baseName}");
      foreach ($config->extList as $extension => $instructions) {
        echo ' -> Building ', $extension, '...', PHP_EOL;
        $fs->write(
          "./php8-extensions/{$baseName}/{$extension}.dockerfile",
          buildDockerfile(
            $dockerTag,
            $config->osSpecs[$osName],
            $instructions['deps'][$osName] ?? $instructions['deps'] ?? [],
            $instructions['make']
          )
        );

        // execute build command
        $proc = new Symfony\Component\Process\Process(
          [
            'docker',
            'build',
            '-f',
            "{$buildPath}/php8-extensions/{$baseName}/{$extension}.dockerfile",
            "{$buildPath}/php8-extensions/{$baseName}/"
          ]
        );
        $proc->run();

        echo " -> Status: \t", $proc->isSuccessful() ? 'PASS' : 'FAIL', PHP_EOL;
        if ($proc->isSuccessful() === false) {
          $fs->write(
            "./php8-extensions/{$baseName}/{$extension}.log",
            $proc->getOutput()
          );
        }
      }
    } catch (Exception $exception) {
      echo 'Exception caught!', PHP_EOL;
      echo $exception->getMessage(), PHP_EOL;
    }
  }
}

function buildDockerfile(string $version, array $osSpecs, array $extDeps, array $extMake): string {
  // build pre-make commands
  $preMake = $osSpecs['pre'];
  $preMake[] = sprintf(
    '%s %s',
    $osSpecs['deps']['cmd'],
    implode(
      ' ',
      array_merge($osSpecs['deps']['list'], $extDeps)
    )
  );

  $content = [];
  $content[] = 'FROM php:'. $version;
  $content[] = 'RUN ' . implode(' && ', $preMake);
  $content[] = 'RUN ' . implode(' && ', $extMake);

  return implode(PHP_EOL, $content);
}
