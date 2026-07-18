<?php

namespace JordJD\ServerInfo\Tests;

use JordJD\ServerInfo\Metrics\Hostname;
use JordJD\ServerInfo\Server;
use JordJD\SSHConnection\SSHCommand;
use JordJD\SSHConnection\SSHConnection;
use PHPUnit\Framework\TestCase;

final class ServerMetricsTest extends TestCase
{
    public function testDefaultMetricsReturnAccurateUnitsAndValues()
    {
        $connection = new FakeSSHConnection([
            '7564013.42',
            'example',
            '29',
            '1000204886016',
            '37',
            '1033347072',
            '0',
            '1073737728',
            '0',
            '1',
            '0',
            '12',
            '0.13  0.19 0.13 1/123 456',
            '6.2',
        ]);

        $metrics = (new Server($connection))->metrics()->toArray();

        $this->assertSame(7564013, $metrics['uptime']);
        $this->assertSame('example', $metrics['hostname']);
        $this->assertSame(29, $metrics['disk-usage-percentage']);
        $this->assertSame(1000204886016, $metrics['total-disk-space-bytes']);
        $this->assertSame(37, $metrics['memory-usage-percentage']);
        $this->assertSame(1033347072, $metrics['total-memory-bytes']);
        $this->assertSame(0, $metrics['swap-usage-percentage']);
        $this->assertSame(1073737728, $metrics['total-swap-bytes']);
        $this->assertTrue($metrics['mysql-server-running']);
        $this->assertFalse($metrics['apache-server-running']);
        $this->assertTrue($metrics['nginx-server-running']);
        $this->assertSame(12, $metrics['active-http-connections']);
        $this->assertSame([1 => 0.13, 5 => 0.19, 15 => 0.13], $metrics['load-averages']);
        $this->assertSame(6.2, $metrics['cpu-usage-percentage']);

        $commands = implode("\n", $connection->commands);
        $this->assertNotFalse(strpos($commands, 'df -P -B1 /'));
        $this->assertNotFalse(strpos($commands, '$2 * 1024'));
        $this->assertNotFalse(strpos($commands, 'command -v ss'));
        $this->assertNotFalse(strpos($commands, 'pgrep -x mariadbd'));
        $this->assertFalse(strpos($commands, '<(free)'));
    }

    public function testCanRequestOnlySelectedMetrics()
    {
        $connection = new FakeSSHConnection(['selected-host']);
        $metrics = (new Server($connection))->metrics([Hostname::class])->toArray();

        $this->assertSame(['hostname' => 'selected-host'], $metrics);
        $this->assertCount(1, $connection->commands);
    }

    public function testRejectsInvalidMetricClass()
    {
        $this->expectException(\InvalidArgumentException::class);
        (new Server(new FakeSSHConnection([])))->metrics([\stdClass::class]);
    }
}

final class FakeSSHConnection extends SSHConnection
{
    private $outputs;
    public $commands = [];

    public function __construct(array $outputs)
    {
        $this->outputs = $outputs;
    }

    public function isConnected(): bool
    {
        return true;
    }

    public function run(string $command): SSHCommand
    {
        $this->commands[] = $command;

        if (!$this->outputs) {
            throw new \RuntimeException('No fake SSH output remains for command: '.$command);
        }

        return new FakeSSHCommand(array_shift($this->outputs));
    }
}

final class FakeSSHCommand extends SSHCommand
{
    private $fakeOutput;

    public function __construct($output)
    {
        $this->fakeOutput = (string) $output;
    }

    public function getOutput(): string
    {
        return trim($this->fakeOutput);
    }
}
