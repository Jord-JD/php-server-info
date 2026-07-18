<?php

namespace JordJD\ServerInfo\Metrics;

class DiskUsagePercentage extends BaseMetric
{
    public function populate()
    {
        $command = $this->connection->run('LC_ALL=C df -P -B1 / | awk \'NR == 2 {gsub(/%/, "", $5); print $5}\'');

        $output = $command->getOutput();

        if (is_numeric($output) && $output >= 0 && $output <= 100) {
            $this->value = (int) $output;
        }
    }

    public function getName()
    {
        return 'disk-usage-percentage';
    }

}
