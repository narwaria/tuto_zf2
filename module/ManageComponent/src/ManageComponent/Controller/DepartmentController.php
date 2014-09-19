<?php

namespace ManageComponent\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use ManageComponent\Model\Department;

use ManageComponent\Form\DepartmentForm;
use ManageComponent\Form\DepartmentFormSearch;
use ManageComponent\Form\DepartmentFilter;

use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Adapter\DbTable as DbTableAuthAdapter;

class DepartmentController extends AbstractActionController {
	
	protected $deptTable;
    protected $authservice;
    protected $config;
    protected $pagin;
    protected $page;
    
    public function construct() { 
		// code is here
	}

    public function indexAction() {
        //global setting
        $config = $this->getServiceLocator()->get('Config');
        $pagin  = $config['common']['items_per_page'];
        
        $this->deptTable	= $this->getServiceLocator()->get('DepartmentTable'); 
        $this->page			= (int)$this->params()->fromQuery('page', 1);
        
        $authResponse = $this->getAuthService()->getStorage()->read();
        
        $this->layout()->setVariable('auth',$authResponse);
        $this->layout()->setVariable('menuid',2);
    	$this->layout('layout/dashboard');
    	
    	//form submited values
        $dept_name         = trim($this->params()->fromQuery('dept_name'));
        
        //set preserve value to view
    	$DeptFormSearch = new DepartmentFormSearch();
        $DeptFormSearch->get('dept_name')->setValue($dept_name);
    	
        // pagination
        $depts = $this->deptTable->fetchAll($dept_name, TRUE);
        $depts->setCurrentPageNumber($this->page);
        $depts->setItemCountPerPage($pagin);
        
        return new ViewModel(array(
            'form'		=>$DeptFormSearch,
            'order_by'	=> '',
            'order'		=> '',
            'page'		=> $this->page,
            'paginator'	=> '',
            'search_by'	=>'',
            'dept_name'	=> $dept_name,
            'depts'		=>$depts
        ));
    }

    public function addAction() {
        $this->deptTable	= $this->getServiceLocator()->get('DepartmentTable');
        
        $authResponse		= $this->getAuthService()->getStorage()->read();
        
        $this->layout()->setVariable('auth',$authResponse);
        $this->layout()->setVariable('menuid',2);
        $this->layout('layout/dashboard'); //  Set the layout
        
    	$DeptForm	= new DepartmentForm();
        
        $request	= $this->getRequest();   
       
        if ($request->isPost()) {
            
            $request->getPost()->set('client_id', $authResponse['client_id']);
            $request->getPost()->set('created_by_user_id', $authResponse['user_id']);
            $request->getPost()->set('created_date', date("Y-m-d H:i:s"));
            
            $deptFilter    = new DepartmentFilter();
			$DeptForm->setInputFilter($deptFilter);
			$DeptForm->setData($request->getPost());
            
            if ($DeptForm->isValid()) {
                
                $dept = new Department();
                $dept->exchangeArray($DeptForm->getData());
                
                $this->deptTable->saveDepartment($dept);
                
                // Redirect to list of skill
                return $this->redirect()->toRoute('department');
            }
            
        }
        return array('form' => $DeptForm);
    }

    public function editAction() {
        $this->deptTable	= $this->getServiceLocator()->get('DepartmentTable'); 
        
        $authResponse		= $this->getAuthService()->getStorage()->read();
        
        $this->layout()->setVariable('auth',$authResponse);
        $this->layout()->setVariable('menuid',2);
        $this->layout('layout/dashboard');
        
        $dept_id   = (int)$this->params()->fromRoute('dept_id', 0);

        if ($dept_id==0) {
            return $this->redirect()->toRoute('department', array( 'action' => 'add'));
        }
        
        $deptDetail	= $this->deptTable->getDepartment($dept_id);

        $DeptForm	= new DepartmentForm();
        $request    = $this->getRequest();  
        $DeptForm->bind($deptDetail);
        $DeptForm->get('page')->setValue($this->page);
        $DeptForm->get('submit')->setAttribute('value', 'Edit Department');

        if ($request->isPost()) {
            
            $deptFilter= new DepartmentFilter();
			$DeptForm->setInputFilter($deptFilter);
			$DeptForm->setData($request->getPost());
            
            if ($DeptForm->isValid()) {
                
                $dept = new Department();
                $dept->exchangeArray($DeptForm->getData());
                
                $this->deptTable->updateDepartment($dept);
                // Redirect to list of dept
                return $this->redirect()->toRoute('department');
            }
            
        }
        
        return array('dept_id'  =>  $dept_id, 'form' => $DeptForm,);
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

