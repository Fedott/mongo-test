<?php

namespace Fedot\MongoTest\Command;

use Doctrine\ODM\MongoDB\Cursor;
use Fedot\MongoTest\Console\Command;
use Fedot\MongoTest\Model\Counter;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CheckCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('check')
            ->addArgument('serialNumber', InputArgument::REQUIRED);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln("Start check");

        $serialNumber = $input->getArgument('serialNumber');

        $startCount = $serialNumber * Counter::COUNTER;
        $stopCount = ($serialNumber + 1) * Counter::COUNTER;

        $documentManager = $this->getDocumentManager();
        $counterRepository = $documentManager->getRepository(Counter::class);

        /** @var Cursor $counterCursor */
        $counterCursor = $counterRepository->createQueryBuilder()
            ->find()
            ->field('count')->gte($startCount)
            ->field('count')->lt($stopCount)
            ->getQuery()
            ->execute();

        $countCounters = $counterCursor->count();
        $output->writeln("Count counters: $countCounters");
        $output->writeln("$startCount | $stopCount");

        $prevCounterValue = null;

        /** @var Counter $counter */
        foreach ($counterCursor as $counter) {
            if ($prevCounterValue !== null && $prevCounterValue != $counter->count - 1) {
                $output->writeln("Missing count. Prev: $prevCounterValue Current: {$counter->count}");
            }

            $prevCounterValue = $counter->count;

            $documentManager->clear();
        }

        $output->writeln("All checked");
    }
}
