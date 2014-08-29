<?php

namespace Test;
 
use Zend\ModuleManager\Feature\AutoloaderProviderInterface,
    Zend\ModuleManager\Feature\ConfigProviderInterface,
    Zend\ModuleManager\Feature\ViewHelperProviderInterface;    
 
class Module implements
    AutoloaderProviderInterface, 
    ConfigProviderInterface, 
    ViewHelperProviderInterface
{
    public function getAutoloaderConfig(){/*common code*/}
    public function getConfig(){ /*common code */ }
 
    public function getViewHelperConfig()
    {
        return array(
            'factories' => array(
                'test_helper' => function($sm) {
                    $helper = new View\Helper\Testhelper ;
                    return $helper;
                }
            )
        );   
   }
}