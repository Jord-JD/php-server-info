<?php

namespace JordJD\ServerInfo\Metrics;

class Uptime extends BaseMetric
{
    public function populate()
    {
        $command = $this->connection->run('cat /proc/uptime | cut -d " " -f 1');

        $output = $command->getOutput();

        if (is_numeric($output) && $output >= 0) {
            $this->value = (int) $output;
        }
    }

    public function getName()
    {
        return 'uptime';
    }

}
