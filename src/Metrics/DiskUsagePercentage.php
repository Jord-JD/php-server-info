<?php

namespace JordJD\ServerInfo\Metrics;

class DiskUsagePercentage extends BaseMetric
{
    public function populate()
    {
        $command = $this->connection->run('df / --output=pcent | tail -n 1 | cut -d "%" -f 1');

        $output = $command->getOutput();

        if ($output) {
            $this->value = (int) $output;
        }
    }

    public function getName()
    {
        return 'disk-usage-percentage';
    }

}