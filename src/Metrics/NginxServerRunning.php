<?php

namespace JordJD\ServerInfo\Metrics;

class NginxServerRunning extends BaseMetric
{
    public function populate()
    {
        $output = $this->connection->run('pgrep -x nginx >/dev/null 2>&1; printf $?')->getOutput();

        $this->value = trim($output) === '0';
    }

    public function getName()
    {
        return 'nginx-server-running';
    }

}
