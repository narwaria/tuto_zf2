<?php
namespace ManageComponent\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use ManageComponent\Model\Topic;
use ManageComponent\Form\TopicForm;
use ManageComponent\Form\TopicFormSearch;

use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Adapter\DbTable as DbTableAuthAdapter;

class TopicController extends AbstractActionController
{
    protected $topicTable; 
    protected $technologyTable;	
    protected $authservice;    
    public function indexAction()
    {
        $authResponse = $this->getAuthService()->getStorage()->read();
        $this->layout()->setVariable('auth',$authResponse);
        $this->layout()->setVariable('menuid',3);
    	$this->layout('layout/dashboard');
    	$TopicFormSearch = new TopicFormSearch();
    	$topics = $this->getTopicTable()->fetchAll();
        return new ViewModel(array(
                    'form'=>$TopicFormSearch,
                    'order_by' => '',
                    'order' => '',
                    'page' => '',
                    'paginator' => '',
                    'search_by'=>'',
                    'topics'=>$topics
                )); 
    }
    public function addAction()
    {
        $authResponse = $this->getAuthService()->getStorage()->read();
        $this->layout()->setVariable('auth',$authResponse);
        $this->layout()->setVariable('menuid',3);
        $this->layout('layout/dashboard'); //  Set the layout
        $technologyArray = $this->getTechnologyList();     	 
    	$TopicForm = new TopicForm();              
        $TopicForm->get('technologies')->setValueOptions($technologyArray);
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

    public function editAction()
    {
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

    public function deleteAction()
    {
        return new ViewModel();
    }

    public function getTechnologyList(){
        $technologiesValues =   $this->getTechnologyTable()->fetchAll(); // get the technology names
        $technologyArray    =   array();
        foreach($technologiesValues as  $technology){
            $technologyArray[$technology["id"]]     =   $technology["technology"];        
        }
        return $technologyArray;
    }

    public function getTopicTable()
    {       
        if (!$this->topicTable) {            
            $sm = $this->getServiceLocator();          
            $this->topicTable = $sm->get('ManageComponent\Model\TopicTable');
        }
        return $this->topicTable;
    }
    public function getTechnologyTable()
    {       
        if (!$this->technologyTable) {            
            $sm = $this->getServiceLocator();          
            $this->technologyTable = $sm->get('ManageComponent\Model\TechnologyTable');           
        }
        return $this->technologyTable;
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

