<?php

namespace Src\Core\Commands;

use InvalidArgumentException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;

class ServeCommand extends Command
{
    protected static $defaultName = 'serve';

    private Process $process;

    protected function configure(): void
    {
        $this
        ->setDescription('Starts a development server')
        ->setHelp('You can provide an optional host and port, for the development server.')
        ->addOption('host', null, InputOption::VALUE_REQUIRED)
        ->addOption('port', null, InputOption::VALUE_REQUIRED);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $base = getcwd() . '/public';
        $host = '127.0.0.1';
        $port = 8000;

        $this->handleSignals();
        $this->startProcess($host, $port, $base, $output);

        return Command::SUCCESS;
    }

    private function command(string $host, int $port, string $base): array
    {
        return [
        PHP_BINARY,
        "-S",
        "{$host}:{$port}",
        '-t',
        "{$base}",
        ];
    }

    private function handleSignals(): void
    {
        pcntl_async_signals(true);

        pcntl_signal(SIGTERM, function ($signal) {
            if ($signal === SIGTERM) {
                $this->process->signal(SIGKILL);
                exit;
            }
        });
    }

    private function startProcess(string $host, int $port, string $base, OutputInterface $output): void
    {
        $this->process = new Process($this->command($host, $port, $base), $base);
        $this->process->setTimeout(PHP_INT_MAX);
        $this->process->start(function (string $err, string $buffer) use ($output) {
            $output->write("<info>{$buffer}</info>");
        });

        $output->writeln("Serving requests at http://{$host}:{$port}");

        $this->process->wait();
    }
}