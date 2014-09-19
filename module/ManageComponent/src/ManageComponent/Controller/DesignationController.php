<?php

namespace ManageComponent\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use ManageComponent\Model\Designation;

use ManageComponent\Form\DesignationForm;
use ManageComponent\Form\DesignationFormSearch;
use ManageComponent\Form\DesignationFilter;

use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Adapter\DbTable as DbTableAuthAdapter;

class DesignationController extends AbstractActionController {
	
	protected $deptTable;
	protected $desigTable;
	protected $authservice;
	protected $config;
	protected $pagin;
	protected $page;
	protected $client_id;

    public function indexAction() {
        //global setting
        $config = $this->getServiceLocator()->get('Config');
        $pagin  = $config['common']['items_per_page']; 
        
        $this->deptTable	= $this->getServiceLocator()->get('DepartmentTable'); 
		$this->desigTable	= $this->getServiceLocator()->get('DesignationTable');
		$this->page			= (int)$this->params()->fromQuery('page', 1);
        
        $authResponse		= $this->getAuthService()->getStorage()->read();

        $this->layout()->setVariable('auth',$authResponse);
        $this->layout()->setVariable('menuid',2);
    	$this->layout('layout/dashboard');
        
        //dropdownd array values
        $deptArray	= $this->deptTable->getDeptList();
        
        //form submited values
        $desig_name = trim($this->params()->fromQuery('desig_name'));
        $dept_id	= trim($this->params()->fromQuery('depts'));
        
        //set preserve value to view
    	$DesignFormSearch = new DesignationFormSearch(); 
        $DesignFormSearch->get('desig_name')->setValue($desig_name);
        $DesignFormSearch->get('depts')->setValue($dept_id);
        $DesignFormSearch->get('depts')->setValueOptions($deptArray);
        
        // pagination
        $desigs = $this->desigTable->fetchAll(array('desig_name'=>$desig_name, 'dept_id'=>$dept_id), TRUE);
        $desigs->setCurrentPageNumber($this->page);
        $desigs->setItemCountPerPage($pagin);
        
        return new ViewModel(array(
            'form'		=>$DesignFormSearch,
            'order_by'	=> '',
            'order'		=> '',
            'page'		=> $this->page,
            'paginator'	=> '',
            'search_by'	=>'',
            'desig_name'=> $desig_name,
            'desigs'	=>$desigs
        ));
    }

    public function addAction() {
        
        $this->deptTable	= $this->getServiceLocator()->get('DepartmentTable'); 
		$this->desigTable	= $this->getServiceLocator()->get('DesignationTable');
		
        $authResponse		= $this->getAuthService()->getStorage()->read();
        
        $this->layout()->setVariable('auth',$authResponse);
        $this->layout()->setVariable('menuid',2);
        $this->layout('layout/dashboard'); //  Set the layout
        
        $deptArray         = $this->deptTable->getDeptList();
        
    	$DesigForm = new DesignationForm();
        $DesigForm->get('depts')->setValueOptions($deptArray);
        
        $request = $this->getRequest();   
       
        if ($request->isPost()) {
			
			$request->getPost()->set('client_id', $authResponse['client_id']);
            $request->getPost()->set('created_by_user_id', $authResponse['user_id']);
            $request->getPost()->set('created_date', date("Y-m-d H:i:s"));
            
            $desigFilter    = new DesignationFilter();
			$DesigForm->setInputFilter($desigFilter);
			$DesigForm->setData($request->getPost());
            
            if ($DesigForm->isValid()) {
                
                $desig = new Designation();
                $desig->exchangeArray($DesigForm->getData());
                
                $this->desigTable->saveDesig($desig);
                
                // Redirect to list of topic
                return $this->redirect()->toRoute('designation');
            }
            
        }
        return array('form' => $DesigForm); 
    }

    public function editAction() {
        $this->deptTable	= $this->getServiceLocator()->get('DepartmentTable'); 
		$this->desigTable	= $this->getServiceLocator()->get('DesignationTable');
		
        $authResponse		= $this->getAuthService()->getStorage()->read();
        
        $this->layout()->setVariable('auth',$authResponse);
        $this->layout()->setVariable('menuid',2);
        $this->layout('layout/dashboard');
        
        $desig_id   = (int)$this->params()->fromRoute('desig_id', 0);

        if ($desig_id==0) {
			return $this->redirect()->toRoute('designation', array( 'action' => 'add'));
        }
        
        $deptArray	= $this->deptTable->getDeptList();
        $desigDetail= $this->desigTable->getDesig($desig_id);
        
        $DesigForm  = new DesignationForm();
        $request    = $this->getRequest();  
        $DesigForm->bind($desigDetail);
        $DesigForm->get('depts')->setValue($desigDetail['dept_id']);
        $DesigForm->get('depts')->setValueOptions($deptArray);
        $DesigForm->get('page')->setValue($this->page);
        $DesigForm->get('submit')->setAttribute('value', 'Edit Designation');

        if ($request->isPost()) {
            
            $desigFilter    = new DesignationFilter();
			$DesigForm->setInputFilter($desigFilter);
			$DesigForm->setData($request->getPost());
            
            if ($DesigForm->isValid()) {
                
                $desig = new Designation();
                $desig->exchangeArray($DesigForm->getData());
                
                $this->desigTable->updateDesig($desig);
                
                // Redirect to list of desig
                return $this->redirect()->toRoute('designation');
            }
            
        }
        
        return array('desig_id'  =>  $desig_id, 'form' => $DesigForm,);
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

