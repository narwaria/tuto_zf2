<?php

namespace TopicManagement\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use TopicManagement\Form\TopicForm;
use TopicManagement\Form\TopicFormSearch;

use Zend\View\Model\ViewModel;

use TopicManagement\Model\Topic;


use Zend\Db\Sql\Select;
use Zend\Paginator\Paginator;
use Zend\Paginator\Adapter\Iterator as paginatorIterator;




class TopicManagementController extends AbstractActionController
{

    protected $topicTable;
    public $searchurl=null;
    
    public function indexAction() {
        
         $form = new TopicFormSearch();
         $request = $this->getRequest();
         
        
         $form->setData($request->getPost());
          
        
        $select = new Select();
        $order_by = $this->params()->fromRoute('order_by') ?
                $this->params()->fromRoute('order_by') : 'topic_id';
        $order = $this->params()->fromRoute('order') ?
                $this->params()->fromRoute('order') : Select::ORDER_ASCENDING;
        $page = $this->params()->fromRoute('page') ? (int) $this->params()->fromRoute('page') : 1;
        
        
        //admin/topic/page/1/order_by/topic_id/ASC
        $search_by=$this->params()->fromRoute('search_by')?$this->params()->fromRoute('search_by'):"{}";
        
        $this->searchurl='/admin/topic/page/1/order_by/topic_id/ASC';
        if ($request->isPost()) {            
            $formdata    = (array) $request->getPost();
            $search_data = array();
            foreach ($formdata as $key => $value) {
                if ($key != 'submit') {
                    if (!empty($value)) {
                        $search_data[$key] = $value;
                    }
                }
            }
            if (!empty($search_data)) {
                $search_by = json_encode($search_data);
                $this->searchurl .= "/search_by/".$search_by;
            }
            //echo $this->searchurl; die;
            //return $this->redirect()->toRoute();
            $this->redirect()->toUrl($this->searchurl);
        }
               
        $where    = new \Zend\Db\Sql\Where();
        $formdata = array();
        if (!empty($search_by)) {
            $formdata = (array) json_decode($search_by);
            if (!empty($formdata['topic_name'])) {
                $form->get('topic_name')->setAttribute('value', $formdata['topic_name']);
                $where->addPredicate(
                        new \Zend\Db\Sql\Predicate\Like('topic_name', '%' . $formdata['topic_name'] . '%')
                );
            }
            if (!empty($formdata['topic_description'])) {
                $form->get('topic_description')->setAttribute('value', $formdata['topic_description']);
                $where->addPredicate(
                        new \Zend\Db\Sql\Predicate\Like('topic_description', '%' . $formdata['topic_description'] . '%')
                );
            }            
        }
        if (!empty($where)) {
            $select->where($where);
        }
        

        $albums = $this->getTopicTable()->fetchAll($select->order($order_by . ' ' . $order));
        $itemsPerPage = 2;

        $albums->current();
        $paginator = new Paginator(new paginatorIterator($albums));
        $paginator->setCurrentPageNumber($page)
                ->setItemCountPerPage($itemsPerPage)
                ->setPageRange(5);
       
                
        return new ViewModel(array(
                    'form'=>$form,
                    'order_by' => $order_by,
                    'order' => $order,
                    'page' => $page,
                    'paginator' => $paginator,
                    'search_by'=>$search_by
                )); 
    }
    
    public function AddTopicAction()
    {
        return new ViewModel();
    }

    public function ViewTopicAction()
    {
        return new ViewModel();
    }

    public function EditTopicAction()
    {
       
        return new ViewModel();
    }

    public function addAction()
    {
        $form = new TopicForm();
        $request = $this->getRequest();        
        if ($request->isPost()) {
            
            $topic = new Topic();
            
            $form->setInputFilter($topic->getInputFilter());
            $form->setData($request->getPost());
            
            if ($form->isValid()) {
                $topic->exchangeArray($form->getData());                
                $this->getTopicTable()->saveTopic($topic);
                
                // Redirect to list of topic
                return $this->redirect()->toRoute('topic');
            }
            
        }
        return array('form' => $form);
        //return new ViewModel();
    }

    public function editAction()
    {        
        $id = (int) $this->params()->fromRoute('topic_id', 0);        
        if (!$id) {
            return $this->redirect()->toRoute('topid', array( 'action' => 'add'));
        }
        $topic= $this->getTopicTable()->getTopic($id);
        

        $form = new TopicForm();
        $form->bind($topic);        
        $form->get('submit')->setAttribute('value', 'Edit');
        
        $request = $this->getRequest();        
        if ($request->isPost()) {
            $form->setInputFilter($topic->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {                
                $this->getTopicTable()->saveTopic($form->getData());

                // Redirect to list of albums
                return $this->redirect()->toRoute('topic');
            }
        }

        return array(
            'topic_id'   => $id,
            'form' => $form,
        );
        
        //return new ViewModel();
    }

    public function deleteAction()
    {
        return new ViewModel();
    }

    public function viewAction()
    {
        return new ViewModel();
    }
    public function getTopicTable()
    {       
        if (!$this->topicTable) {            
            $sm = $this->getServiceLocator();          
            $this->topicTable = $sm->get('TopicManagement\Model\TopicTable');
        }        
        return $this->topicTable;
    }

}

