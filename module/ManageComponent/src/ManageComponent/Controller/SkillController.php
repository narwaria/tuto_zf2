<?php

namespace ManageComponent\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use ManageComponent\Model\Skill;

use ManageComponent\Form\SkillForm;
use ManageComponent\Form\SkillFormSearch;
use ManageComponent\Form\SkillFilter;

use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Adapter\DbTable as DbTableAuthAdapter;

class SkillController extends AbstractActionController {
	
    protected $skillTable;
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
        $this->page			= (int)$this->params()->fromQuery('page', 1);
        
        $authResponse = $this->getAuthService()->getStorage()->read();
        
        $this->layout()->setVariable('auth',$authResponse);
        $this->layout()->setVariable('menuid',2);
    	$this->layout('layout/dashboard');
    	
    	//form submited values
        $skill_name         = trim($this->params()->fromQuery('skill_name'));
        
        //set preserve value to view
    	$SkillFormSearch = new SkillFormSearch();
        $SkillFormSearch->get('skill_name')->setValue($skill_name);
        //$SkillFormSearch->get('skills')->setValueOptions($skillArray);
    	
        // pagination
        $skills = $this->skillTable->fetchAll($skill_name, TRUE);
        $skills->setCurrentPageNumber($this->page);
        $skills->setItemCountPerPage($pagin);
        
        return new ViewModel(array(
            'form'=>$SkillFormSearch,
            'order_by' => '',
            'order' => '',
            'page' => $this->page,
            'paginator' => '',
            'search_by'=>'',
            'skill_name' => $skill_name,
            'skills'=>$skills
        ));
        
    }

    public function addAction() {
        $this->skillTable   = $this->getServiceLocator()->get('SkillTable'); 
        
        $authResponse = $this->getAuthService()->getStorage()->read();
        
        $this->layout()->setVariable('auth',$authResponse);
        $this->layout()->setVariable('menuid',2);
        $this->layout('layout/dashboard'); //  Set the layout
        
    	$SkillForm	= new SkillForm();
        
        $request	= $this->getRequest();   
       
        if ($request->isPost()) {
            
            $request->getPost()->set('client_id', $authResponse['client_id']);
            $request->getPost()->set('created_by_user_id', $authResponse['user_id']);
            $request->getPost()->set('created_date', date("Y-m-d H:i:s"));
            
            $skillFilter    = new SkillFilter();
			$SkillForm->setInputFilter($skillFilter);
			$SkillForm->setData($request->getPost());
            
            if ($SkillForm->isValid()) {
                
                $skill = new Skill();
                $skill->exchangeArray($SkillForm->getData());
                
                $this->skillTable->saveSkill($skill);
                
                // Redirect to list of skill
                return $this->redirect()->toRoute('skill');
            }
            
        }
        return array('form' => $SkillForm);
    }

    public function editAction() {
        $this->skillTable   = $this->getServiceLocator()->get('SkillTable'); 
        $authResponse		= $this->getAuthService()->getStorage()->read();
        
        $this->layout()->setVariable('auth',$authResponse);
        $this->layout()->setVariable('menuid',2);
        $this->layout('layout/dashboard');
        
        $skill_id   = (int)$this->params()->fromRoute('skill_id', 0);

        if ($skill_id==0) {
            return $this->redirect()->toRoute('skill', array( 'action' => 'add'));
        }
        
        $skillDetail= $this->skillTable->getSkill($skill_id);
                
        $SkillForm  = new SkillForm();
        $request    = $this->getRequest();  
        $SkillForm->bind($skillDetail);
        $SkillForm->get('page')->setValue($this->page);
        $SkillForm->get('submit')->setAttribute('value', 'Edit Skill');

        if ($request->isPost()) {
            
            $skillFilter= new SkillFilter();
			$SkillForm->setInputFilter($skillFilter);
			$SkillForm->setData($request->getPost());
            
            if ($SkillForm->isValid()) {
                
                $skill = new Skill();
                $skill->exchangeArray($SkillForm->getData());
                
                $this->skillTable->updateSkill($skill);
                // Redirect to list of skill
                return $this->redirect()->toRoute('skill');
            }
            
        }
        
        return array('skill_id'  =>  $skill_id, 'form' => $SkillForm,);
    }

    public function deleteAction() {
        return new ViewModel();
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

