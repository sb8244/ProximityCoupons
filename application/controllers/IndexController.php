<?php

use Application\Model\Document\User;
class IndexController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        /* @var $em \Doctrine\ODM\MongoDB\DocumentManager */
        $em = Zend_Registry::get('em');
        $userModel = $em->getRepository('Application\Model\Document\User');
        $res = $userModel->findAll();
        
        foreach($res as $item) 
            \Doctrine\Common\Util\Debug::dump($item);
        die();
    }


}

