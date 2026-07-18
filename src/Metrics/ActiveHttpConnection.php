<?php

namespace JordJD\ServerInfo\Metrics;

class ActiveHttpConnection extends BaseMetric
{
    public function populate()
    {
        $output = $this->connection->run(
            'if command -v ss >/dev/null 2>&1; then ss -Htan state established \'( sport = :80 or sport = :443 )\' | wc -l; else netstat -an 2>/dev/null | awk \'$6 == "ESTABLISHED" && ($4 ~ /:(80|443)$/) {count++} END {print count+0}\'; fi'
        )->getOutput();

        if (is_numeric($output) && $output >= 0) {
            $this->value = (int) $output;
        }
    }

    public function getName()
    {
        return 'active-http-connections';
    }
}
