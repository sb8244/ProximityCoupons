<?php

namespace Application\Model\Document;

class User extends \Shanty_Mongo_Document
{
    protected static $_db = "yhack";
    protected static $_collection = 'User';
    
    protected static $_requirements = array(
            'password' => 'Required',
            'email' => array('Required', 'Validator:EmailAddress'),
            'claims' => 'Validator:Array',
            'claims.$' => 'Validator:MongoId'
    );
}