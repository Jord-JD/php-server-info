<?php

use JordJD\ServerInfo\Server;
use JordJD\SSHConnection\SSHConnection;

require_once __DIR__.'/../vendor/autoload.php';

$connection = (new SSHConnection())
    ->to(getenv('TEST_HOSTNAME'))
    ->as(getenv('TEST_USERNAME'))
    ->withPrivateKey('/home/jordan/.ssh/id_rsa');

$array = (new Server($connection))
    ->metrics()
    ->toArray();

var_dump($array);
