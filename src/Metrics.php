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
use InvalidArgumentException;
use ReflectionClass;

class Metrics
{
    private $server;

    public const DEFAULT_METRIC_CLASSES = [
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

    private $metricClasses;

    public function __construct(Server $server, ?array $metricClasses = null)
    {
        $this->server = $server;
        $this->metricClasses = $metricClasses === null ? self::DEFAULT_METRIC_CLASSES : $metricClasses;

        foreach ($this->metricClasses as $metricClass) {
            if (!is_string($metricClass) || !is_subclass_of($metricClass, MetricInterface::class) || !(new ReflectionClass($metricClass))->isInstantiable()) {
                throw new InvalidArgumentException('Metric classes must implement MetricInterface.');
            }
        }
    }

    public function all(): array
    {
        return array_map(function ($metricClass) {
            return new $metricClass($this->server);
        }, $this->metricClasses);
    }

    public function toArray()
    {
        $values = [];

        foreach ($this->all() as $metric) {
            $values = array_merge($values, $metric->toArray());
        }

        return $values;
    }
}
