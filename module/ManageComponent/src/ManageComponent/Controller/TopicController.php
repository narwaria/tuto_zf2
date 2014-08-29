<?php
namespace ManageComponent\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use ManageComponent\Model\Topic;
use ManageComponent\Form\TopicForm;
use ManageComponent\Form\TopicFormSearch;


class TopicController extends AbstractActionController
{
	protected $topicTable;	

    public function indexAction()
    {
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
    	$this->layout('layout/dashboard'); // 
    	$TopicForm = new TopicForm();
        $request = $this->getRequest();        
        if ($request->isPost()) {            
            $topic = new Topic();            
            $TopicForm->setInputFilter($topic->getInputFilter());
            $TopicForm->setData($request->getPost());            
            if ($TopicForm->isValid()) {
                $topic->exchangeArray($TopicForm->getData());
                $this->getTopicTable()->saveTopic($topic);
                // Redirect to list of topic
                return $this->redirect()->toRoute('topic');
            }
            
        }
        return array('form' => $TopicForm);    	
    }

    public function editAction()
    {
        return new ViewModel();
    }

    public function deleteAction()
    {
        return new ViewModel();
    }

    public function getTopicTable()
    {       
        if (!$this->topicTable) {            
            $sm = $this->getServiceLocator();          
            $this->topicTable = $sm->get('ManageComponent\Model\TopicTable');
        }        
        return $this->topicTable;
    }

}

