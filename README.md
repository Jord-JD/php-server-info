# PHP Server Info

[![Tests](https://github.com/Jord-JD/php-server-info/actions/workflows/tests.yml/badge.svg)](https://github.com/Jord-JD/php-server-info/actions/workflows/tests.yml)
[![Packagist](https://img.shields.io/packagist/v/jord-jd/php-server-info.svg)](https://packagist.org/packages/jord-jd/php-server-info)

Collect Linux server metrics through an existing SSH connection.

## Installation

```bash
composer require jord-jd/php-server-info
```

## Usage

```php
<?php

use JordJD\ServerInfo\Server;
use JordJD\SSHConnection\SSHConnection;

require_once __DIR__.'/../vendor/autoload.php';

$connection = (new SSHConnection())
    ->to('example.com')
    ->as('username')
    ->withPrivateKey('/home/user/.ssh/id_rsa');

$array = (new Server($connection))
    ->metrics()
    ->toArray();

var_dump($array);
```

```php
array(14) {
  ["uptime"]=>
  int(7564013)
  ["hostname"]=>
  string(11) "example"
  ["disk-usage-percentage"]=>
  int(29)
  ["total-disk-space-bytes"]=>
  int(1000204886016)
  ["memory-usage-percentage"]=>
  int(37)
  ["total-memory-bytes"]=>
  int(1033347072)
  ["swap-usage-percentage"]=>
  int(26)
  ["total-swap-bytes"]=>
  int(1073737728)
  ["mysql-server-running"]=>
  bool(true)
  ["apache-server-running"]=>
  bool(false)
  ["nginx-server-running"]=>
  bool(true)
  ["active-http-connections"]=>
  int(0)
  ["load-averages"]=>
  array(3) {
    [1]=>
    float(0.13)
    [5]=>
    float(0.19)
    [15]=>
    float(0.13)
  }
  ["cpu-usage-percentage"]=>
  float(6.2)
}

```

Byte-named metrics are returned as actual bytes. Disk total is the total size of
the root filesystem, not its remaining free space. A metric returns `null` when
its command output is unavailable or cannot be validated; a server with no swap
configured reports zero swap usage.

## Selecting metrics

To avoid unnecessary SSH commands, pass only the metric classes you need:

```php
use JordJD\ServerInfo\Metrics\Hostname;
use JordJD\ServerInfo\Metrics\LoadAverages;

$metrics = (new Server($connection))
    ->metrics([Hostname::class, LoadAverages::class])
    ->toArray();
```

Custom metric classes may also be supplied when they implement
`JordJD\ServerInfo\Interfaces\MetricInterface`.

## Remote requirements

The target is expected to be a Linux server with `/proc`, POSIX shell tools,
`awk`, `df`, and `top`. Active HTTP connections use `ss` when available and
fall back to `netstat`. Service checks use `pgrep` and recognize both MySQL and
MariaDB process names.

## Compatibility

PHP 7.1 through the current PHP 8.x releases are supported. Version 3 and the
forthcoming version 4 of `jord-jd/php-ssh-connection` are accepted, allowing
Composer to select the newest release compatible with the running PHP version.

### Upgrading to 4.0

Earlier releases mislabeled KiB values as bytes and returned available disk
space from `total-disk-space-bytes`. Version 4 returns actual byte values and
the root filesystem's total capacity. Consumers that store or threshold these
three metrics should update their expected values accordingly.
