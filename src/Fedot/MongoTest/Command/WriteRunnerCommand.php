<?php

namespace Fedot\MongoTest\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;

class WriteRunnerCommand extends StoppableCommand
{
    /**
     * @var Process[]
     */
    protected $processes = [];

    protected function configure()
    {
        $this->setName('write')
            ->setDescription('Running many write-cycle commands')
            ->addArgument('count', InputArgument::REQUIRED);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->initStopSignal();

        $count = $input->getArgument('count');
        $output->writeln("Start $count cycles");

        for ($i = 0; $i < $count; $i++) {
            $process = new Process('./bin/test write-cycle ' . ($i+1));
            $this->processes[] = $process;
            $process->start();
        }

        $output->writeln("Processes started");

        while (1) {
            $this->dispatchSignal();

            if ($this->stopSignal) {
                $output->writeln("Stopping " . count($this->processes) . " processes");

                foreach ($this->processes as $process) {
                    $process->stop();
                }

                $output->writeln("All processes stopped");

                break;
            }

            sleep(1);
        }

        $output->writeln("<info>Goodbye!</info>");
    }
}