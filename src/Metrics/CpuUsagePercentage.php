<?php

namespace JordJD\ServerInfo\Metrics;

class CpuUsagePercentage extends BaseMetric
{
    public function populate()
    {
        $output = $this->connection
            ->run('LC_ALL=C top -bn1 | awk \'/^%?Cpu/ {for (i=1; i<=NF; i++) if ($i ~ /^id,?$/) {gsub(/,/, "", $(i-1)); printf "%.1f", 100-$(i-1); exit}}\'')
            ->getOutput();

        if (!is_numeric($output)) {
            $this->value = null;
            return;
        }

        $usage = (float) $output;

        if ($usage < 0 || $usage > 100) {
            $this->value = null;
            return;
        }

        $this->value = $usage;
    }

    public function getName()
    {
        return 'cpu-usage-percentage';
    }

}
