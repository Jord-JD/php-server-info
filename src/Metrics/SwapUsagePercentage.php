<?php

namespace JordJD\ServerInfo\Metrics;

class SwapUsagePercentage extends BaseMetric
{
    public function populate()
    {
        $output = $this->connection->run(
            'awk \'/^SwapTotal:/ {t=$2} /^SwapFree:/ {f=$2} END {if (t==0) print 0; else printf "%.0f", (t-f)/t*100}\' /proc/meminfo'
        )->getOutput();

        if (is_numeric($output) && $output >= 0 && $output <= 100) {
            $this->value = (int) $output;
        }
    }

    public function getName()
    {
        return 'swap-usage-percentage';
    }

}
