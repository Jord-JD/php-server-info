<?php

namespace JordJD\ServerInfo\Metrics;

class MemoryUsagePercentage extends BaseMetric
{
    public function populate()
    {
        $used = (int) $this->connection->run('awk \'/^Mem/ {print $3}\' <(free)')->getOutput();
        $total = (int) $this->connection->run('awk \'/^Mem/ {print $2}\' <(free)')->getOutput();

        if ($used && $total) {
            $this->value = (int) round($used / $total * 100);
        }
    }

    public function getName()
    {
        return 'memory-usage-percentage';
    }

}