<?php
namespace TopicManagement;

// Add these import statements:
use TopicManagement\Model\Topic;
use TopicManagement\Model\TopicTable;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;

class Module
{
  

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
       /* return array(
            'factories' => array(
                'TopicManagement\Model\TopicTable' =>  function($sm) {
                    $tableGateway = $sm->get('TopicTableGateway');
                    $table = new TopicTable($tableGateway);
                    return $table;
                },
                'TopicTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Topic());
                    return new TableGateway('tbl_topic', $dbAdapter, null, $resultSetPrototype);
                },
            ),
        );*/
        return array(
            'factories' => array(
                'TopicManagement\Model\TopicTable' =>  function($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $table = new TopicTable($dbAdapter);
                    return $table;
                },
            ),
        );
    }
    
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }
        
}
