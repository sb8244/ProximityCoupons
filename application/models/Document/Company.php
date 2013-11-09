<?php 

namespace Application\Model\Document;

class Company extends \Shanty_Mongo_Document
{
    protected static $_db = "yhack";
    protected static $_collection = 'Company';
    
    protected static $_requirements = array(
    		'password' => 'Required',
    		'email' => array('Required', 'Validator:EmailAddress'),
    		'active_coupons' => 'Array',
    		'active_coupons.$' => 'Validator:MongoId',
            'inactive_coupons' => 'Array',
    		'inactive_coupons.$' => 'Validator:MongoId'
    );
}