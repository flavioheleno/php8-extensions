# PHP 8 Extensions Builder

This script is a simple automation on checking extensions status with the upcoming PHP 8 release.

Content about extension's PHP 8 support:

- [PHP extensions status with upcoming PHP 8.0](https://blog.remirepo.net/post/2020/09/21/PHP-extensions-status-with-upcoming-PHP-8.0)

## Dependencies

As in any modern PHP project, dependencies are handled by [composer](https://getcomposer.org/).

To install dependencies, run `composer install`.

## Definitions

### OS Support

All OS support (i.e. enable/disable) is handled by `$osMatrix` [here](https://github.com/flavioheleno/php8-extensions/blob/master/builder.php#L6).

The currently supported OSes are `alpine` and `buster`, from the tags available in the [official PHP Docker Image](https://hub.docker.com/_/php?tab=tags).

For a given OS, one may add the following instructions:

- Pre-build commands to be executed, by adding commands to `$osSpecs[<os-name>]['pre']` list (e.g. [here](https://github.com/flavioheleno/php8-extensions/blob/master/builder.php#L13)).

- Build dependencies to be installed, by adding:

  1. The dependency installation command to `$osSpecs[<os-name>]['deps']['cmd']` (e.g. [here](https://github.com/flavioheleno/php8-extensions/blob/master/builder.php#L18));
  2. The list of dependencies to `$osSpecs[<os-name>]['deps']['list']` (e.g. [here](https://github.com/flavioheleno/php8-extensions/blob/master/builder.php#L19)).

### PHP Version Support

All PHP version is handled by `$phpVerMatrix` [here](https://github.com/flavioheleno/php8-extensions/blob/master/builder.php#L34).

One possible future improvement is to decouple variants (i.e. `zts`, `cli`, `fpm`) from version, by spliting `$phpVerMatrix` into two, but at this point this is simple enough to work with.

### Extension Support

This is the actual core of the script, where one can add/remove extensions and it is all handled by `$extList` [here](https://github.com/flavioheleno/php8-extensions/blob/master/builder.php#L39).

For a given extension, one may add the following instructions:

- Build dependencies to be installed, by adding:

  1. OS-Specific dependencies to `$extList[<ext-name>]['deps'][<os-name>]` list (e.g. [here](https://github.com/flavioheleno/php8-extensions/blob/master/builder.php#L42));
  2. OS-Independant dependencies to `$extList[<ext-name>]['deps']` list.

- Build commands to be executed, by adding commands to `$extList[<ext-name>]['make']` list (e.g. [here](https://github.com/flavioheleno/php8-extensions/blob/master/builder.php#L45)).

### Build path

The build path is the directory where the script will output all docker-related files, it is recomended to be a temporary directory (such as `/tmp`) and this is handled by `$buildPath` [here](https://github.com/flavioheleno/php8-extensions/blob/master/builder.php#L78).

## Running

Once everything is set, execute the script by running `php ./builder.php`.

The output should look like this:

```bash
$ php builder.php
Creating build files for PHP v8.0.0beta4@alpine
 -> Building decimal...
 -> Status:   FAIL
 -> Building parallel...
 -> Status:   FAIL
 -> Building pcov...
 -> Status:   FAIL
Creating build files for PHP v8.0.0beta4@buster
 -> Building decimal...
 -> Status:   FAIL
 -> Building parallel...
 -> Status:   FAIL
 -> Building pcov...
 -> Status:   FAIL
Creating build files for PHP v8.0.0beta4-zts@alpine
 -> Building decimal...
 -> Status:   FAIL
 -> Building parallel...
 -> Status:   FAIL
 -> Building pcov...
 -> Status:   FAIL
Creating build files for PHP v8.0.0beta4-zts@buster
 -> Building decimal...
 -> Status:   FAIL
 -> Building parallel...
 -> Status:   FAIL
 -> Building pcov...
 -> Status:   FAIL
```
