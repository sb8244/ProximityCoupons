<?php

use Application\Model\Document\Coupon;
use Application\Model\Document\Company;
use Application\Model\CouponFinder;
use Application\Model\Document\User;
class CouponController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function detailAction()
    {
        $this->_helper->_layout->setLayout('user');
        $id = $this->_getParam("id");
        $coupon = Coupon::find($id);
        $company = Company::find($coupon->company);
        $this->view->coupon = $coupon;
        $this->view->company = $company;
    }
    
    public function getclaimedAction()
    {
        $user = Zend_Registry::get('user');
        $claims = $user->claims->export();
        $coupons = Coupon::fetchAll(array('_id' => array('$in' => $claims )))->export();
        echo json_encode($coupons);
        die();
    }
    
    public function redeemAction()
    {
        $user = Zend_Registry::get("user");
        $id = $this->_getParam("id");
        $lat = $this->_getParam("lat");
        $lng = $this->_getParam("lng");
        $results = array();
        if($id && $lat && $lng)
        {
            $finder = new CouponFinder($user);
            $result = $finder->checkDeal($id, $lat, $lng);
            if($result === true) {
                //now let's apply the deal for the user!
                $results['status'] = 200;
                $coupon = Coupon::find($id);
                
                $coupon->addToSet('claimed_by', $user->getId());
                $user->addToSet('claims', $coupon->getId());
                
                $coupon->save();
                $user->save();
                
            } else {
                $results['status'] = 500;
                $results['error'] = $result;
            }
        }
        else
        {
            $results['status'] = 404;
            $results['error'] = "All Parameters Required";
        }
        die(json_encode($results));
    }
    
    public function locateAction()
    {
        $lat = $this->_getParam("lat");
        $lng = $this->_getParam("lng");
        if($lat && $lng)
        {
            $user = Zend_Registry::get("user");
            $finder = new CouponFinder($user);
            $deals = $finder->locateDeals($lat, $lng);
            foreach($deals as $k=>$deal)
            {
                $coupon = $deal['coupon']->export(true);
                unset($coupon['_type']);
                unset($coupon['claimed_by']);
                
                $deals[$k]['coupon'] = $coupon;
            }
            echo json_encode($deals);
        }
        die();
    }

    public function createAction()
    {
        $results = array();
        if($this->getRequest()->isPost())
        {
            $data = $_POST;
            $notEmptyValidator = new Zend_Validate_NotEmpty();
            $float = new Zend_Validate_Float();
            
            $errors = array();
            if(!$float->isValid($data['lat']))
                $errors['lat'] = 'Please click a point on the map';
            if(!$float->isValid($data['long']))
            	$errors['long'] = 'Please click a point on the map';
            if(!$notEmptyValidator->isValid($data['title']))
                $errors['title'] = 'Please enter a title';
            if(!$notEmptyValidator->isValid($data['description']))
                $errors['description'] = 'Please enter a description';
            if(!$notEmptyValidator->isValid($data['proximity']))
                $errors['proximity'] = 'Please select a proximity';

            if(count($errors) > 0)
            {
                $results['status'] = 500;
                $results['errors'] = $errors;
            }
            else
            {
                $coupon = new Coupon();
                $company = Zend_Registry::get('user');
                
                $coupon->claimed_by = array();
                $coupon->title = $data['title'];
                $coupon->description = $data['description'];
                $coupon->position->type = 'Point';
                $coupon->position->coordinates = array(floatval($data['long']), floatval($data['lat']));
                $coupon->proximity = $data['proximity'];
                $coupon->company = $company->getId();
                $company->addToSet('active_coupons', $coupon->getId());
                
                $company->save();
                $coupon->save();
                
                $results['status'] = 200;
            }
        }
        else
        {
            $results['status'] = 404;
        }
        die(json_encode($results));
    }
}

