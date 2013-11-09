<?php

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
    
    protected function _initMongoDoctrine()
    {
    	require 'Doctrine/Common/ClassLoader.php';
    
    	$classLoader = new \Doctrine\Common\ClassLoader('Doctrine');
    	$classLoader->register();
    	
    	\Doctrine\Common\Annotations\AnnotationRegistry::registerFile(APPLICATION_PATH . '/../library/Doctrine/ODM/MongoDB/Mapping/Annotations/DoctrineAnnotations.php');
    
    	$entitiesPath = APPLICATION_PATH . '/models/Document';
    	$proxiesPath  = APPLICATION_PATH . '/models/Proxy';
    	$hydratorPath  = APPLICATION_PATH . '/models/Hydrator';
    
    	$config = new \Doctrine\ODM\MongoDB\Configuration();
    	$driverImpl = $config->newDefaultAnnotationDriver($entitiesPath);
    
    	$config->setMetadataDriverImpl($driverImpl);
    	$config->setProxyDir($proxiesPath);
    	$config->setProxyNamespace('domain\Proxies');
    	$config->setHydratorDir($hydratorPath);
    	$config->setHydratorNamespace('domain\Hydrators');
    	$config->setAutoGenerateProxyClasses(true);
    	$config->setAutoGenerateHydratorClasses(true);
    	$config->setDefaultDB("yhack");
    
    	$em = \Doctrine\ODM\MongoDB\DocumentManager::create(new \Doctrine\MongoDB\Connection(), $config);
    	Zend_Registry::set('em', $em);
    		
    	return $em;
    }
}

