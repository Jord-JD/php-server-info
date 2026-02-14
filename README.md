# PHP Server Info

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
  int(18045964)
  ["memory-usage-percentage"]=>
  int(37)
  ["total-memory-bytes"]=>
  int(1009128)
  ["swap-usage-percentage"]=>
  int(26)
  ["total-swap-bytes"]=>
  int(1048572)
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