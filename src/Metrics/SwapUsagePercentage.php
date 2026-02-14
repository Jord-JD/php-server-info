<?php

namespace JordJD\ServerInfo\Metrics;

class SwapUsagePercentage extends BaseMetric
{
    public function populate()
    {
        $used = (int) $this->connection->run('awk \'/^Swap/ {print $3}\' <(free)')->getOutput();
        $total = (int) $this->connection->run('awk \'/^Swap/ {print $2}\' <(free)')->getOutput();

        if ($used && $total) {
            $this->value = (int) round($used / $total * 100);
        }
    }

    public function getName()
    {
        return 'swap-usage-percentage';
    }

}