<?php
namespace ManageComponent\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use ManageComponent\Model\Topic;

use ManageComponent\Form\TopicForm;
use ManageComponent\Form\TopicFormSearch;
use ManageComponent\Form\TopicFilter;

use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Adapter\DbTable as DbTableAuthAdapter;

//use Zend\ServiceManager;

class TopicController extends AbstractActionController
{
    protected $departmentTable;
    protected $designationTable;
    protected $skillTable;
    protected $topicTable;	
    protected $authservice;
    protected $config;
    protected $pagin;
    protected $page;


    public function __construct() { 
        //echo $sm = $this->getServiceLocator()->get('ManageComponent\Model\SkillTable');
        //$this->$skillTable = $sm->get('ManageComponent\Model\SkillTable');
    }
    
    public function indexAction() {
        
        //global setting
        $config = $this->getServiceLocator()->get('Config');
        $pagin  = $config['common']['items_per_page']; 
        
		$this->skillTable	= $this->getServiceLocator()->get('SkillTable'); 
		$this->topicTable	= $this->getServiceLocator()->get('TopicTable');
        $this->page			= (int)$this->params()->fromQuery('page', 1);
        
        $authResponse = $this->getAuthService()->getStorage()->read();

        $this->layout()->setVariable('auth',$authResponse);
        $this->layout()->setVariable('menuid',2);
    	$this->layout('layout/dashboard');
        
        //dropdownd array values
        $skillArray         = $this->skillTable->getSkillList();
        
        //form submited values
        $topic_name         = trim($this->params()->fromQuery('topic_name'));
        
        //set preserve value to view
    	$TopicFormSearch = new TopicFormSearch();
        $TopicFormSearch->get('topic_name')->setValue($topic_name);
        $TopicFormSearch->get('skills')->setValueOptions($skillArray);
    	
        // pagination
        $topics = $this->topicTable->fetchAll($topic_name, TRUE);
        $topics->setCurrentPageNumber($this->page);
        $topics->setItemCountPerPage($pagin);
        
        return new ViewModel(array(
            'form'=>$TopicFormSearch,
            'order_by' => '',
            'order' => '',
            'page' => $this->page,
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
        
        $skillArray         = $this->skillTable->getSkillList();
        
    	$TopicForm = new TopicForm();
        $TopicForm->get('skill')->setValueOptions($skillArray);
        
        $request = $this->getRequest();   
       
        if ($request->isPost()) {
            
            $request->getPost()->set('client_id', $authResponse['client_id']);
            $request->getPost()->set('created_by_user_id', $authResponse['user_id']);
            $request->getPost()->set('created_date', date("Y-m-d H:i:s"));
            
            $topicFilter    = new TopicFilter();
			$TopicForm->setInputFilter($topicFilter);
			$TopicForm->setData($request->getPost());
            
            if ($TopicForm->isValid()) {
                
                $topic = new Topic();
                $topic->exchangeArray($TopicForm->getData());
                
                $this->topicTable->saveTopic($topic);
                
                $lastInsertTopicID = $this->topicTable->lastInsertValue;
                
                foreach($request->getPost()->skill as $skillID){
                    $this->topicTable->saveSkillByTopic($lastInsertTopicID,$skillID);
                }
                // Redirect to list of topic
                return $this->redirect()->toRoute('topic');
            }
            
        }
        return array('form' => $TopicForm);    	
    }

    public function editAction() {
        
        $this->topicTable   = $this->getServiceLocator()->get('TopicTable');
        $this->skillTable   = $this->getServiceLocator()->get('SkillTable'); 
        
        $authResponse = $this->getAuthService()->getStorage()->read();
        $this->layout()->setVariable('auth',$authResponse);
        $this->layout()->setVariable('menuid',3);
        $this->layout('layout/dashboard');
        
        $topic_id   = (int)$this->params()->fromRoute('topic_id', 0);

        if ($topic_id==0) {
            return $this->redirect()->toRoute('topid', array( 'action' => 'add'));
        }
        $skillArray		= $this->skillTable->getSkillList();
        $topicDetail	= $this->topicTable->getTopic($topic_id);
        $userSkillArray = $this->topicTable->getSkillByTopic($topic_id);
        
        $userSkills     =   array();
        foreach ($userSkillArray as $u_s_key => $u_s_value) {
            $userSkills[] =   (int)$u_s_value["skill_id"];
        }
        
        $TopicForm  = new TopicForm();
        $request    = $this->getRequest();  
        $TopicForm->bind($topicDetail);
        $TopicForm->get('skill')->setValue($userSkills);
        $TopicForm->get('skill')->setValueOptions($skillArray);
        $TopicForm->get('page')->setValue($this->page);
        $TopicForm->get('submit')->setAttribute('value', 'Edit Topic');

        if ($request->isPost()) {
            
            $topicFilter    = new TopicFilter();
			$TopicForm->setInputFilter($topicFilter);
			$TopicForm->setData($request->getPost());
            
            if ($TopicForm->isValid()) {
                
                $topic = new Topic();
                $topic->exchangeArray($TopicForm->getData());
                
                $this->topicTable->updateTopic($topic);
                $this->topicTable->deleteSkillByTopic($topic_id);
                foreach($request->getPost()->skill as $skillID){
                    $this->topicTable->saveSkillByTopic($topic_id, $skillID);
                }
                // Redirect to list of topic
                return $this->redirect()->toRoute('topic');
            }
            
        }
        
        return array('topic_id'  =>  $topic_id, 'form' => $TopicForm,);
    }

    public function deleteAction() {
        return new ViewModel();
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

