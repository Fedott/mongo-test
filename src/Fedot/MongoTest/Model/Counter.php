<?php

namespace Fedot\MongoTest\Model;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/**
 * @ODM\Document
 */
class Counter
{
    /**
     * @ODM\Id
     *
     * @var string
     */
    public $id;

    /**
     * @ODM\String
     *
     * @var string
     */
    public $count;
}
