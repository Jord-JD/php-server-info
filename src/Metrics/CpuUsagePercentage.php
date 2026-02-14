<?php

namespace JordJD\ServerInfo\Metrics;

class CpuUsagePercentage extends BaseMetric
{
    public function populate()
    {
        $output = $this->connection
            ->run('top -n 1 -b | grep "%Cpu" | cut -d "," -f 4 | cut -d " " -f 2')
            ->getOutput();

        if (!is_numeric($output)) {
            $this->value = null;
            return;
        }

        $idleCpu = (float) $output;

        if ($idleCpu < 0 || $idleCpu > 100) {
            $this->value = null;
            return;
        }

        $this->value = 100 - $idleCpu;
    }

    public function getName()
    {
        return 'cpu-usage-percentage';
    }

}