<?php

namespace JordJD\ServerInfo\Metrics;

class TotalSwapBytes extends BaseMetric
{
    public function populate()
    {
        $command = $this->connection->run('awk \'/^Swap/ {print $2}\' <(free)');

        $output = $command->getOutput();

        if ($output) {
            $this->value = (int) $output;
        }
    }

    public function getName()
    {
        return 'total-swap-bytes';
    }

}