<?php

namespace Dashboard\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Adapter\DbTable as DbTableAuthAdapter;

class DashboardController extends AbstractActionController
{
    protected $topicTable;
    protected $categoryTable;
    protected $authservice;
    public function indexAction()
    {
        
        $authResponse = $this->getAuthService()->getStorage()->read();


    	$ConfigMenu = $this->getServiceLocator()->get('Config');
        //$this->getServiceLocator()->get('TopicTableGateway');
        
    	
        //$topic= new TopicTable();
        $departmentsCount    = $this->getCategoryTable()->getdepartmentCount();
        $designationsCount   = $this->getCategoryTable()->getdesignationCount();
        
         $topicsCount = $this->getTopicTable()->gettopicCount();         

        $components = array("Department" => isset($departmentsCount->departments_count)?$departmentsCount->departments_count:0,
                            "Designation"=> isset($designationsCount->designations_count)?$designationsCount->designations_count:0,
                            "Technology"=>0,
                            "Topic"=>isset($topicsCount)?$topicsCount:0);
        


        $topics = $this->getTopicTable()->fetchAll();


    	$this->layout()->setVariable('auth',$authResponse);
    	$this->layout('layout/dashboard');                
        return new ViewModel(array("topics"=>$topics,'components'=>$components,'auth'=>$authResponse));
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
    
    public function getTopicTable()
    {       
        if (!$this->topicTable) {            
            $sm = $this->getServiceLocator();          
            $this->topicTable = $sm->get('ManageComponent\Model\TopicTable');
        }        
        return $this->topicTable;
    } 
    public function getCategoryTable()
    {       
        if (!$this->categoryTable) {            
            $sm = $this->getServiceLocator();          
            $this->categoryTable = $sm->get('ManageComponent\Model\CategoryTable');
        }        
        return $this->categoryTable;
    }  

}