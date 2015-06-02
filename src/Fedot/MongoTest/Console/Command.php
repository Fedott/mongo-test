<?php

namespace Fedot\MongoTest\Console;

use AI\Tester\Client\API;
use AI\Tester\Model\Counter;
use AI\Tester\Strategy\StrategyManager;
use DI\Container;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\DocumentRepository;
use Symfony\Component\Console\Command\Command as BaseCommand;

class Command extends BaseCommand
{
    /**
     * @var Container
     */
    protected $container;

    /**
     * @param Container $container
     */
    public function setContainer(Container $container)
    {
        $this->container = $container;
    }

    /**
     * @return Container
     */
    public function getContainer()
    {
        return $this->container;
    }

    /**
     * @return DocumentManager
     */
    protected function getDocumentManager()
    {
        return $this->getContainer()->get('doctrine.documentManager');
    }

    /**
     * @return DocumentRepository
     */
    protected function getUserRepository()
    {
        return $this->getDocumentManager()->getRepository(Counter::class);
    }

    /**
     * @param string $username
     * @return Counter
     */
    protected function findUserByUsername($username)
    {
        return $this->getUserRepository()->findOneBy(['username' => $username]);
    }

    /**
     * @return API
     * @throws \DI\NotFoundException
     */
    protected function getApiClient()
    {
        return $this->getContainer()->get(API::class);
    }

    /**
     * @return StrategyManager
     * @throws \DI\NotFoundException
     */
    protected function getStrategyManager()
    {
        $strategyManager = $this->getContainer()->get('strategy.manager');

        return $strategyManager;
    }
}
