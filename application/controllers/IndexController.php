<?php

use Application\Model\Document\User;
use Application\Model\Document\Company;
use Application\Model\Document\Coupon;

class IndexController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        if(Zend_Registry::isRegistered('type'))
        {
            $type = Zend_Registry::get('type');
            if($type == 'company') {
                $this->_forward('index', 'company');
            } else if($type == 'user') {
                $this->_forward('index', 'user');
            }
        }
    }

}

