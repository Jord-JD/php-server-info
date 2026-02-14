<?php

namespace JordJD\ServerInfo\Metrics;

class TotalMemoryBytes extends BaseMetric
{
    public function populate()
    {
        $command = $this->connection->run('awk \'/^Mem/ {print $2}\' <(free)');

        $output = $command->getOutput();

        if ($output) {
            $this->value = (int) $output;
        }
    }

    public function getName()
    {
        return 'total-memory-bytes';
    }

}