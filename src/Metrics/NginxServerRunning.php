<?php

namespace JordJD\ServerInfo\Metrics;

class NginxServerRunning extends BaseMetric
{
    public function populate()
    {
        $output = $this->connection->run('ps -e | grep nginx')->getOutput();

        $this->value = !empty($output);
    }

    public function getName()
    {
        return 'nginx-server-running';
    }

}