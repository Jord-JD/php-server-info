<?php

namespace JordJD\ServerInfo\Metrics;

class ApacheServerRunning extends BaseMetric
{
    public function populate()
    {
        $output = $this->connection->run('pgrep -x apache2 >/dev/null 2>&1 || pgrep -x httpd >/dev/null 2>&1; printf $?')->getOutput();

        $this->value = trim($output) === '0';
    }

    public function getName()
    {
        return 'apache-server-running';
    }

}
