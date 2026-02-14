<?php

namespace JordJD\ServerInfo\Interfaces;

use JordJD\ServerInfo\Server;

interface MetricInterface
{
    public function __construct(Server $server);

    public function populate();

    public function getName();
    public function getValue();
}