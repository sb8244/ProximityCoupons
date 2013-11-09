<?php

use Application\Model\Document\User;
use Application\Model\Document\Coupon;
class UserController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        $this->_helper->_layout->setLayout('user');
        // action body
    }

    public function createAction()
    {
        $form = new \Application\Form\User();
        $this->view->form = $form;
        if($this->getRequest()->isPost())
        {
            $data = $this->getRequest()->getPost();
            if($form->isValid($data))
            {
                $user = new User();
                $user->email = $form->getValue("email");
                $user->password = md5($form->getValue('password'));
                $user->claims = array();
                $user->save();
            }
            else
            {
                $form->populate($data);
            }
        }
        
    }


}



