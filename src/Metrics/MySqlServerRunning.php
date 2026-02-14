<?php

namespace JordJD\ServerInfo\Metrics;

class MySqlServerRunning extends BaseMetric
{
    public function populate()
    {
        $output = $this->connection->run('ps -e | grep mysqld')->getOutput();

        $this->value = !empty($output);
    }

    public function getName()
    {
        return 'mysql-server-running';
    }

}