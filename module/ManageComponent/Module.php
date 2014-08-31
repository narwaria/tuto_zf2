<?php
namespace ManageComponent;

// Add these import statements:
use ManageComponent\Model\Topic;
use ManageComponent\Model\TopicTable;
use ManageComponent\Model\Category;
use ManageComponent\Model\CategoryTable;
use ManageComponent\Model\TechnologyTable;

use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;

class Module
{
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

    public function getServiceConfig()
    {
          return array(
            'factories' => array(
                'ManageComponent\Model\TopicTable' =>  function($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $table = new TopicTable($dbAdapter);
                    return $table;
                },
                'ManageComponent\Model\CategoryTable' =>  function($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $table = new CategoryTable($dbAdapter);
                    return $table;
                },
                'ManageComponent\Model\TechnologyTable' =>  function($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $table = new TechnologyTable($dbAdapter);
                    return $table;
                },
            ),
        );
    }    
}
