<?php

namespace JordJD\ServerInfo;

use JordJD\SSHConnection\SSHConnection;

class Server
{
    private $connection;

    public function __construct(SSHConnection $connection)
    {
        $this->connection = $connection;
        $this->connectIfNecessary();
    }

    public function connectIfNecessary(): void
    {
        if (!$this->connection->isConnected()) {
            $this->connection->connect();
        }
    }

    public function connection(): SSHConnection
    {
        return $this->connection;
    }

    public function metrics(): Metrics
    {
        return new Metrics($this);
    }
}