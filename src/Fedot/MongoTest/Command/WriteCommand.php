<?php

namespace Fedot\MongoTest\Command;

use Fedot\MongoTest\Model\Counter;
use Fedot\MongoTest\Console\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class WriteCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('write-cycle')
            ->addArgument('serialNumber', InputArgument::REQUIRED);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln("Start write");

        $serialNumber = $input->getArgument('serialNumber');

        $startCount = $serialNumber * Counter::COUNTER;

        $documentManager = $this->getDocumentManager();
        $startTime = microtime(true);

        for ($i = $startCount;; $i++) {
            try {
                $counter = new Counter();
                $counter->count = $i;
                $documentManager->persist($counter);
                $documentManager->flush();

                $documentManager->clear();

                if ($i % 10000 == 0) {
                    $stopTime = microtime(true);
                    $time = $stopTime - $startTime;
                    $output->writeln("Current counter: $i, time: $time");
                    $startTime = microtime(true);
                }
            } catch (\MongoCursorException $e) {
                $output->writeln("<error>Error: ". $e->getMessage() ."</error>");
            }
        }
    }
}