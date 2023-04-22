<?php

namespace Src\Core\Commands;

use Src\App\App;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Src\App\Services\QueueService;

class QueueCommand extends Command
{
    protected static $defaultName = 'queue:process';

    protected function configure(): void
    {
        $this
        ->setDescription('Runs tasks that have been queued')
        ->setHelp('This command waits for and runs queued jobs')
        ->addArgument('queue', InputArgument::REQUIRED);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        require getcwd() . '/src/App/config/pathsConstants.php';
        $queue = $input->getArgument('queue');
        $container = App::getInstance(
            [
            CORE_DIR . '/Config/dependencies.php',
            APP_DIR . '/config/dependencies.php',
            ]
        )->getContainer();

        $queueService = $container->get(QueueService::class);
        if ($queueService->isEmpty($queue)) {
            $output->writeln("<error>the $queue queue is empty</error>");
            return Command::FAILURE;
        }
        $output->writeln("<info>processing $queue queue...</info>");

        $errors = $queueService->handleProcessing($queue);

        $output->writeln("<info>processing $queue queue completed with $errors error(s)</info>");
        return Command::SUCCESS;
    }
}