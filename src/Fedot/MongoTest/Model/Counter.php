<?php

namespace Fedot\MongoTest\Model;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/**
 * @ODM\Document
 */
class Counter
{
    const COUNTER = 100000000000000;

    /**
     * @ODM\Id
     *
     * @var string
     */
    public $id;

    /**
     * @ODM\Integer
     *
     * @var string
     */
    public $count;
}
