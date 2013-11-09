<?php

use Doctrine\ODM\MongoDB\Mapping\Driver\AnnotationDriver;
use Doctrine\Common\Persistence\PersistentObject;
use Application\Model\Document\Company;
use Application\Model\Document\User;

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
    protected function _initAutoloader()
    {
    	$loader = function($className) {
    		$className = str_replace('\\', '_', $className);
    		Zend_Loader_Autoloader::autoload($className);
    	};
    
    	$autoloader = Zend_Loader_Autoloader::getInstance();
    	$autoloader->pushAutoloader($loader, 'Application\\');
    }
    
    public function _initNamespace()
    {
    	/*
    	 * Resources in default module
    	*/
    	$resourceLoader = new Zend_Application_Module_Autoloader(array(
    			'basePath'  => APPLICATION_PATH,
    			'namespace' => 'Application',
    	));
    	$resourceLoader->addResourceTypes(array(
    			'event'=> array(
    					'namespace' => 'Plugin',
    					'path' => 'plugin'
    			)
    	));
    }
    
    public function _initMockUser()
    {
        //Zend_Auth::getInstance()->getStorage()->write(array('id'=>'527dadd3ac5597c01200002d', 'type'=>'company'));
        Zend_Auth::getInstance()->getStorage()->write(array('id'=>'527dd5cfac5597c812000036', 'type'=>'user'));
    }
    
    public function _initUser()
    {
        $auth = Zend_Auth::getInstance();
        if($auth->hasIdentity())
        {
            $storage = $auth->getStorage()->read();
            $id = $storage['id'];
            if($storage['type'] == 'company')
            {
                $company = Company::find($id);
                Zend_Registry::set('user', $company);
                Zend_Registry::set('type', 'company');
            }
            else if($storage['type'] == 'user')
            {
                $user = User::find($id);
                Zend_Registry::set('user', $user);
                Zend_Registry::set('type', 'user');
            }
        }
    }
}

