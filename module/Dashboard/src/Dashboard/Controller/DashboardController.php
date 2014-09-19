<?php

namespace Dashboard\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Adapter\DbTable as DbTableAuthAdapter;

class DashboardController extends AbstractActionController
{
    protected $departmentTable;
    protected $designationTable;
    protected $skillTable;
    protected $topicTable;
    protected $categoryTable;
    protected $authservice;
    
    public function indexAction() {
		 echo $plugin = $this->MyFirstPlugin()->doSomething();
                
		$this->departmentTable	= $this->getServiceLocator()->get('DepartmentTable'); 
		$this->designationTable = $this->getServiceLocator()->get('DesignationTable');
		$this->skillTable		= $this->getServiceLocator()->get('SkillTable'); 
		$this->topicTable		= $this->getServiceLocator()->get('TopicTable');
        
        $authResponse = $this->getAuthService()->getStorage()->read();


    	$ConfigMenu = $this->getServiceLocator()->get('Config');
        //$this->getServiceLocator()->get('TopicTableGateway');
        
    	
        //$topic= new TopicTable();
        $departmentsCount	= $this->departmentTable->getDepartmentCount();
        $designationsCount	= $this->designationTable->getDesignationCount();
        $skillsCount		= $this->skillTable->getSkillCount();
        $topicsCount		= $this->topicTable->getTopicCount();
        

        $components = array("Department" => $departmentsCount?$departmentsCount:0,
                            "Designation"=> $designationsCount?$designationsCount:0,
                            "Skill"=> $skillsCount?$skillsCount:0,
                            "Topic"=> $topicsCount?$topicsCount:0);
        


        $topics = $this->topicTable->fetchAll();
        
        $this->layout()->setVariable('auth',$authResponse);
    	$this->layout('layout/dashboard'); 
    	               
        return new ViewModel(array('topics'=>$topics,'components'=>$components,'auth'=>$authResponse));
        
    }

    public function getAuthService() {
        if (! $this->authservice) {
            $dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
            $dbTableAuthAdapter = new DbTableAuthAdapter($dbAdapter, 'tbl_user','email','password', 'MD5(?)');
            $authService = new AuthenticationService();
            $authService->setAdapter($dbTableAuthAdapter);
            $this->authservice = $authService;
        }
        return $this->authservice;
    }
    

}
