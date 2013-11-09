<?php

namespace Application\Model\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/**
 * @ODM\Document
 */
class User
{
    /**
     * @ODM\Id
     */
    public $id;

    /**
     * @ODM\String
     */
    public $email;

    /**
     * @ODM\String
     */
    public $password;
    
}