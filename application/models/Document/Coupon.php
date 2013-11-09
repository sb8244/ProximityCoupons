<?php 

namespace Application\Model\Document;

class Coupon extends \Shanty_Mongo_Document
{
    protected static $_db = "yhack";
    protected static $_collection = 'Coupon';
    
    protected static $_requirements = array(
            'company' => 'Validator:MongoId',
    		'title' => 'Required',
            'description' => 'Required',
            'position' => array('Document', 'Required'),
            'position.coordinates' => 'Required',
            'claimed_by' => 'Validator:Array',
            'claimed_by.$' => 'Validator:MongoId'
    );
}