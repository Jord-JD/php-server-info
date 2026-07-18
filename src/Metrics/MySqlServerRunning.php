<?php

namespace JordJD\ServerInfo\Metrics;

class MySqlServerRunning extends BaseMetric
{
    public function populate()
    {
        $output = $this->connection->run('pgrep -x mysqld >/dev/null 2>&1 || pgrep -x mariadbd >/dev/null 2>&1; printf $?')->getOutput();

        $this->value = trim($output) === '0';
    }

    public function getName()
    {
        return 'mysql-server-running';
    }

}
