<?php

namespace JordJD\ServerInfo\Metrics;

class Hostname extends BaseMetric
{
    public function populate()
    {
        $command = $this->connection->run('hostname');

        $output = $command->getOutput();

        if ($output) {
            $this->value = $output;
        }
    }

    public function getName()
    {
        return 'hostname';
    }

}