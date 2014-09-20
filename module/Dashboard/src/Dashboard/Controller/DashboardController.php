<?php

namespace Dashboard\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Adapter\DbTable as DbTableAuthAdapter;

class DashboardController extends AbstractActionController
{
    protected $deptTable;
    protected $desigTable;
    protected $skillTable;
    protected $topicTable;
    protected $categoryTable;
    protected $authservice;
    
    public function indexAction() {
		
		$this->deptTable	= $this->getServiceLocator()->get('DepartmentTable'); 
		$this->desigTable	= $this->getServiceLocator()->get('DesignationTable');
		$this->skillTable	= $this->getServiceLocator()->get('SkillTable'); 
		$this->topicTable	= $this->getServiceLocator()->get('TopicTable');
        
        $authResponse = $this->getAuthService()->getStorage()->read();

    	$ConfigMenu = $this->getServiceLocator()->get('Config');
      
        $deptCount	= $this->deptTable->getDeptCount();
        $desigCount	= $this->desigTable->getDesigCount();
        $skillsCount= $this->skillTable->getSkillCount();
        $topicsCount= $this->topicTable->getTopicCount();
        

        $components = array("Department" => $deptCount?$deptCount:0,
                            "Designation"=> $desigCount?$desigCount:0,
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
            $dbTableAuthAdapter = new DbTableAuthAdapter($dbAdapter, 'user','email','password', 'MD5(?)');
            $authService = new AuthenticationService();
            $authService->setAdapter($dbTableAuthAdapter);
            $this->authservice = $authService;
        }
        return $this->authservice;
    }
    

}
