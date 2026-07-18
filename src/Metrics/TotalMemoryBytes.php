<?php

namespace JordJD\ServerInfo\Metrics;

class TotalMemoryBytes extends BaseMetric
{
    public function populate()
    {
        $command = $this->connection->run('awk \'/^MemTotal:/ {printf "%.0f", $2 * 1024}\' /proc/meminfo');

        $output = $command->getOutput();

        if (is_numeric($output) && $output >= 0) {
            $this->value = (int) $output;
        }
    }

    public function getName()
    {
        return 'total-memory-bytes';
    }

}
