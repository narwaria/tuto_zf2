<?php
namespace ManageComponent;

// Add these import statements:
use ManageComponent\Model\DepartmentTable;
use ManageComponent\Model\DesignationTable;
use ManageComponent\Model\SkillTable;
use ManageComponent\Model\TopicTable;

//use Zend\Db\ResultSet\ResultSet;
//use Zend\Db\TableGateway\TableGateway;

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

    public function getServiceConfig() {
        return array(
            'factories' => array(
                'DepartmentTable' =>  function($sm) {
					$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
					$table = new DepartmentTable($dbAdapter);
					return $table;
				},
				'DesignationTable' =>  function($sm) {
					$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
					$table = new DesignationTable($dbAdapter);
					return $table;
				},
				'SkillTable' =>  function($sm) {
					$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
					$table = new SkillTable($dbAdapter);
					return $table;
				},
				'TopicTable' =>  function($sm) {
					$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
					$table = new TopicTable($dbAdapter);
					return $table;
				},
            ),
        );
    }    
}
