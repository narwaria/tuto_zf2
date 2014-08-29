<?php

namespace Navigation;


use Zend\View\HelperPluginManager;
use Zend\Permissions\Acl\Role\GenericRole;
use Zend\Permissions\Acl\Resource\GenericResource;
use Zend\Authentication\AuthenticationService;

use Navigation\Acl\Acl;

class Module
{
	protected $sm; // 
	
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }
	
    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

	public function init(\Zend\ModuleManager\ModuleManager $mm)
	{	
		
	}
	
	// FOR Authorization
	public function onBootstrap(\Zend\EventManager\EventInterface $e) // use it to attach event listeners
	{
	
	}	
	
    public function getViewHelperConfig()
    {		
        return array(
            'factories' => array(
                // This will overwrite the native navigation helper
                'navigation' => function(HelperPluginManager $pm) {
					

		// Get an instance of the proxy helper
		$navigation = $pm->get('Zend\View\Helper\Navigation');
					
                // Store ACL and role in the proxy helper:		    
                //$navigation->setAcl($acl)
                //           ->setRole($role); // 'member'
                // Return the new navigation helper instance
                return $navigation;
                }
            )
        );
    }
	
}