<?php

namespace JordJD\ServerInfo\Metrics;

class TotalDiskSpaceBytes extends BaseMetric
{
    public function populate()
    {
        $command = $this->connection->run('LC_ALL=C df -P -B1 / | awk \'NR == 2 {print $2}\'');

        $output = $command->getOutput();

        if (is_numeric($output) && $output >= 0) {
            $this->value = (int) $output;
        }
    }

    public function getName()
    {
        return 'total-disk-space-bytes';
    }

}
