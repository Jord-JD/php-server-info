<?php

namespace JordJD\ServerInfo\Metrics;

class TotalDiskSpaceBytes extends BaseMetric
{
    public function populate()
    {
        $command = $this->connection->run('df / --output=avail | tail -n 1');

        $output = $command->getOutput();

        if ($output) {
            $this->value = (int) $output;
        }
    }

    public function getName()
    {
        return 'total-disk-space-bytes';
    }

}