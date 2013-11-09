<?php

use Application\Model\Document\Company;
use Application\Model\Document\Coupon;
class CompanyController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        $company = Zend_Registry::get('user');
        $activeCouponIDs = array_merge($company->active_coupons);
        $activeCoupons = array();
        foreach($activeCouponIDs as $id) {
            $coupon = Coupon::find($id);
            if($coupon)
                $activeCoupons[] = $coupon;
        } 
        
        $this->view->activeCoupons = $activeCoupons;
    }

    public function createAction()
    {
        $form = new \Application\Form\Company();
        $this->view->form = $form;
        if($this->getRequest()->isPost())
        {
            $data = $this->getRequest()->getPost();
            if($form->isValid($data))
            {
                $company = new Company();
                $company->email = $form->getValue("email");
                $company->password = md5($form->getValue('password'));
                $company->active_coupons = array();
                $company->inactive_coupons = array();
                $company->save();
            }
            else
            {
                $form->populate($data);
            }
        }
        
    }
    

}



