<?php

namespace JordJD\ServerInfo;

use JordJD\ServerInfo\Interfaces\MetricInterface;
use JordJD\ServerInfo\Metrics\ActiveHttpConnection;
use JordJD\ServerInfo\Metrics\ApacheServerRunning;
use JordJD\ServerInfo\Metrics\CpuUsagePercentage;
use JordJD\ServerInfo\Metrics\DiskUsagePercentage;
use JordJD\ServerInfo\Metrics\LoadAverages;
use JordJD\ServerInfo\Metrics\MemoryUsagePercentage;
use JordJD\ServerInfo\Metrics\Hostname;
use JordJD\ServerInfo\Metrics\MySqlServerRunning;
use JordJD\ServerInfo\Metrics\NginxServerRunning;
use JordJD\ServerInfo\Metrics\SwapUsagePercentage;
use JordJD\ServerInfo\Metrics\TotalDiskSpaceBytes;
use JordJD\ServerInfo\Metrics\TotalMemoryBytes;
use JordJD\ServerInfo\Metrics\TotalSwapBytes;
use JordJD\ServerInfo\Metrics\Uptime;

class Metrics
{
    private $server;

    private const METRIC_CLASSES = [
        Uptime::class,
        Hostname::class,
        DiskUsagePercentage::class,
        TotalDiskSpaceBytes::class,
        MemoryUsagePercentage::class,
        TotalMemoryBytes::class,
        SwapUsagePercentage::class,
        TotalSwapBytes::class,
        MySqlServerRunning::class,
        ApacheServerRunning::class,
        NginxServerRunning::class,
        ActiveHttpConnection::class,
        LoadAverages::class,
        CpuUsagePercentage::class,
    ];

    public function __construct(Server $server)
    {
        $this->server = $server;
    }

    public function all(): array
    {
        return array_map(function($metricClass) {
            return new $metricClass($this->server);
        }, self::METRIC_CLASSES);
    }

    public function toArray()
    {
        $values = [];

        foreach($this->all() as $metric) {
            $values = array_merge($values, $metric->toArray());
        }

        return $values;
    }

}