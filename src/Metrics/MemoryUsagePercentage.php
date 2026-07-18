<?php

namespace JordJD\ServerInfo\Metrics;

class MemoryUsagePercentage extends BaseMetric
{
    public function populate()
    {
        $output = $this->connection->run(
            'awk \'/^MemTotal:/ {t=$2} /^MemAvailable:/ {a=$2} /^MemFree:/ {f=$2} /^Buffers:/ {b=$2} /^Cached:/ {c=$2} END {if (t>0) {if (a=="") a=f+b+c; printf "%.0f", (t-a)/t*100}}\' /proc/meminfo'
        )->getOutput();

        if (is_numeric($output) && $output >= 0 && $output <= 100) {
            $this->value = (int) $output;
        }
    }

    public function getName()
    {
        return 'memory-usage-percentage';
    }

}
