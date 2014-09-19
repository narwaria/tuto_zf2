<?php
namespace ManageComponent\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use ManageComponent\Model\Topic;
use ManageComponent\Form\TopicForm;
use ManageComponent\Form\TopicFormSearch;

use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Adapter\DbTable as DbTableAuthAdapter;

use Zend\ServiceManager;

class TopicController extends AbstractActionController
{
    protected $topicTable; 
    protected $skillTable;	
    protected $authservice;
    
    public function __construct() { 
        //echo $sm = $this->getServiceLocator()->get('ManageComponent\Model\SkillTable');
        //$this->$skillTable = $sm->get('ManageComponent\Model\SkillTable');
    }
    
    public function indexAction() {
        
        $this->topicTable   = $this->getServiceLocator()->get('TopicTable');
        $this->skillTable   = $this->getServiceLocator()->get('SkillTable'); 
        
        $authResponse = $this->getAuthService()->getStorage()->read();
        
        $this->layout()->setVariable('auth',$authResponse);
        $this->layout()->setVariable('menuid',3);
    	$this->layout('layout/dashboard');
        
        //dropdownd array values
        $departmentArray    = $this->getDepartmentList();
        $designationrray    = $this->getDesignationList();
        $skillArray         = $this->getSkillList();
        
        //form submited values
        $topic_name         = trim($this->params()->fromQuery('topic_name'));
        
        //set preserve value to view
    	$TopicFormSearch = new TopicFormSearch();
        $TopicFormSearch->get('topic_name')->setValue($topic_name);
        $TopicFormSearch->get('departments')->setValueOptions($departmentArray);
        $TopicFormSearch->get('designations')->setValueOptions($designationrray);
        $TopicFormSearch->get('skills')->setValueOptions($skillArray);
    	
        $topics = $this->topicTable->fetchAll($topic_name, TRUE);
        $topics->setCurrentPageNumber((int)$this->params()->fromQuery('page', 1));
        $topics->setItemCountPerPage(2);
        
        return new ViewModel(array(
            'form'=>$TopicFormSearch,
            'order_by' => '',
            'order' => '',
            'page' => '',
            'paginator' => '',
            'search_by'=>'',
            'topic_name' => $topic_name,
            'topics'=>$topics
        )); 
    }
    
    public function addAction() {
        
        $this->topicTable   = $this->getServiceLocator()->get('TopicTable');
        $this->skillTable   = $this->getServiceLocator()->get('SkillTable'); 
        
        $authResponse = $this->getAuthService()->getStorage()->read();
        $this->layout()->setVariable('auth',$authResponse);
        $this->layout()->setVariable('menuid',3);
        $this->layout('layout/dashboard'); //  Set the layout
        
        
        $skillArray = $this->getSkillList();  
        
    	$TopicForm = new TopicForm();              
        $TopicForm->get('skill')->setValueOptions($skillArray);
        $request = $this->getRequest();   
       
        if ($request->isPost()) {
            
            $topic = new Topic();            
            $TopicForm->setInputFilter($topic->getInputFilter());
            $TopicForm->setData($request->getPost());            
            
            if ($TopicForm->isValid()) {
                $topic->exchangeArray($TopicForm->getData());
                $this->getTopicTable()->saveTopic($topic);
                /* @var $lastInsertTopicID Topic */
                $lastInsertTopicID = $this->getTopicTable()->lastInsertValue;
                foreach($request->getPost()->technologies as $technologyID){
                    $this->getTopicTable()->saveTopicTechnologyRelation($lastInsertTopicID,$technologyID);
                }
                // Redirect to list of topic
                return $this->redirect()->toRoute('topic');
            }
            
        }
        return array('form' => $TopicForm);    	
    }

    public function editAction() {
        $this->layout('layout/dashboard');
        $topic_id = (int) $this->params()->fromRoute('topic_id', 0);
        if ($topic_id==0) {
            return $this->redirect()->toRoute('topid', array( 'action' => 'add'));
        }
        $technologyArray = $this->getTechnologyList();
        $topic  = $this->getTopicTable()->getTopic($topic_id);

        $technlogiesArray           =   $this->getTopicTable()->getTopicTechnologyRelation($topic_id); 
        $selectedTechnologies       =   array();
        foreach ($technlogiesArray as $key => $value) {
            $selectedTechnologies[] =   (int)$value["tech_id"];
        }

        $TopicForm  = new TopicForm();
        $request = $this->getRequest();  
        $TopicForm->bind($topic);
        $TopicForm->get('technologies')->setValue($selectedTechnologies);
        $TopicForm->get('technologies')->setValueOptions($technologyArray);     
        $TopicForm->get('submit')->setAttribute('value', 'Edit Topic');

        if ($request->isPost()) {
            $topic = new Topic();            
            $TopicForm->setInputFilter($topic->getInputFilter());
            $TopicForm->setData($request->getPost());            
            if ($TopicForm->isValid()) {
                $topic->exchangeArray($TopicForm->getData());
                $this->getTopicTable()->saveTopic($topic);
                $this->getTopicTable()->deleteTopicTechnologyRelation($topic_id);
                foreach($request->getPost()->technologies as $technologyID){
                    $this->getTopicTable()->saveTopicTechnologyRelation($topic_id,$technologyID);
                }
                // Redirect to list of topic
                return $this->redirect()->toRoute('topic');
            }
            
        }
        return array(
            'topic_id'  =>  $topic_id,
            'form'      =>  $TopicForm
        );
    }

    public function deleteAction() {
        return new ViewModel();
    }

    public function getDepartmentList(){ 
        $skillsValues =   $this->skillTable->fetchAll(); // get the skill names
        
        $skillArray    =   array();
        foreach($skillsValues as  $skill){
            $skillArray[$skill["skill_id"]]     =   $skill["skill_name"];        
        }
        return $skillArray;
    }
    
    public function getDesignationList(){ 
        $skillsValues =   $this->skillTable->fetchAll(); // get the skill names
        
        $skillArray    =   array();
        foreach($skillsValues as  $skill){
            $skillArray[$skill["skill_id"]]     =   $skill["skill_name"];        
        }
        return $skillArray;
    }
    
    public function getSkillList(){ 
        $skillsValues =   $this->skillTable->fetchAll(); // get the skill names
        
        $skillArray    =   array();
        foreach($skillsValues as  $skill){
            $skillArray[$skill["skill_id"]]     =   $skill["skill_name"];        
        }
        return $skillArray;
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

