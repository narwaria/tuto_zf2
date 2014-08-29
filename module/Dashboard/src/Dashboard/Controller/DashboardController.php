<?php

namespace Dashboard\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class DashboardController extends AbstractActionController
{
    protected $topicTable;
    protected $categoryTable;
    public function indexAction()
    {
    	//$ConfigMenu = $this->getServiceLocator()->get('Config');
        //$this->getServiceLocator()->get('TopicTableGateway');

    	//$ConfigMenu["interview_menus"]
        //$topic= new TopicTable();
        $departmentsCount    = $this->getCategoryTable()->getdepartmentCount();
        $designationsCount   = $this->getCategoryTable()->getdesignationCount();
        
         $topicsCount = $this->getTopicTable()->gettopicCount();         

        $components = array("Department" => isset($departmentsCount->departments_count)?$departmentsCount->departments_count:0,
                            "Designation"=> isset($designationsCount->designations_count)?$designationsCount->designations_count:0,
                            "Technology"=>0,
                            "Topic"=>isset($topicsCount)?$topicsCount:0);
        


        $topics = $this->getTopicTable()->fetchAll();
    	$this->layout()->setVariable('LeftMenu', "alok");

    	$this->layout('layout/dashboard');
        return new ViewModel(array("topics"=>$topics,'components'=>$components));
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