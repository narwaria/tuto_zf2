<?php

namespace Users\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Adapter\DbTable as DbTableAuthAdapter;
//use Zend\Mail;

use Users\Model\User;
//use Users\Model\UserTable;

use Users\Form\LoginForm;
use Users\Form\LoginFilter;

use Users\Form\RegisterForm;
use Users\Form\RegisterFilter;


class UsersController extends AbstractActionController {

    protected $authservice;
    protected $userTable;
    
    public function getUserTable(){
        if (!$this->userTable) {
            $sm = $this->getServiceLocator();
            $this->userTable = $sm->get('Users\Model\UserTable');
        }
        return $this->userTable;
    }

    public function indexAction() { 
        //return new ViewModel();
        $view = new ViewModel();
		$view->setTemplate('users/users/index');
		return $view;
    }
    
    public function loginAction() { 
        
        $message = $this->flashmessenger()->getMessages();
        
        $form = new LoginForm();
		$view = new ViewModel(array('form' =>$form, 'message'=>isset($message[0])?$message[0]:null));
		$view->setTemplate('users/users/login');
		return $view;
	}
	
    public function loginProcessAction() { 
		
        //form validating and initializing form post variales
        $post = $this->request->getPost();
        if(!$post) {
			return $this->redirect()->toRoute(NULL , array('controller' => 'users', 'action' => 'login'));
		} else {
            $email      = $post['email'];
            $password   = $post['password'];
        }
        
        /*
		$form		= new LoginForm();
		$inputFilter= new LoginFilter();
        
		$form->setInputFilter($inputFilter);
		$form->setData($post);
        */
               
		$this->getAuthService()->getAdapter()->setIdentity($email)->setCredential($password);
		$result = $this->getAuthService()->authenticate();
		
        //validate login auth
		if (!$result->isValid()) {
            
            $usersDetail = $this->getUserTable()->getUserByEmail($email);
            
            if($usersDetail) {
                //update last login attemp
                $dataArr = array('id'=>$usersDetail->id, 'logLastAttmp'=>date("Y-m-d H:i:s"), 'logFailedCount'=>$usersDetail->logFailedCount+1);
                $this->getUserTable()->updateLastLogin($dataArr);
                $this->flashmessenger()->addMessage($usersDetail->logFailedCount+1 . " envalid attempt.");
            } else {
                $this->flashmessenger()->addMessage("Your are not registered.");
            }
            
			return $this->redirect()->toRoute(NULL , array('controller' => 'users', 'action' => 'login'));
		} else {
			
			//get user detail and assing value for global access
			$usersDetail = $this->getUserTable()->getUserByEmail($email);
            
			$this->getAuthService()->getStorage()->write(array(
                'id' => $usersDetail->id,
				'email'=> $usersDetail->email,
                'name'=> $usersDetail->name,
                'address'=> $usersDetail->address,
                'phone'=> $usersDetail->phone,
                'logLastAttmp'=> $usersDetail->logLastAttmp,
                'logFailedCount'=>$usersDetail->logFailedCount,
			));
            
            //update last login attemp
            $dataArr = array('id'=>$usersDetail->id, 'logLastAttmp'=>date("Y-m-d H:i:s"), 'logFailedCount'=>'0');
            //$user = new User();
            $this->getUserTable()->updateLastLogin($dataArr);
            //$this->getUserTable()->saveUser();
            
            
            ///////////// send email
			/*$message = new Mail\Message();
			$transport = new Mail\Transport\Sendmail();
			
			$message->addFrom("alok.singh@stigasoft.com", "AlokS");
			$message->addTo("sanjay.stigasoft@gmail.com", "SanjayS");
			$message->setSubject("Sending an email from Zend\Mail!");
			$message->setBody("This is the message body.");
			$transport->send($message); */
			///////////
			
			return $this->redirect()->toRoute(NULL , array('controller' => 'users', 'action' => 'loginsuccess'));
		}
	}
	
	public function loginsuccessAction() {
        
        if (! $this->getAuthService()->hasIdentity()){
            return $this->redirect()->toRoute(NULL, array('controller'=>'users', 'action'=>'login'));
        } else {
        	$authResponse = $this->getAuthService()->getStorage()->read();
            
            $view = new ViewModel(array('dataArr'=>$authResponse));
            $view->setTemplate('users/users/loginsuccess');
            return $view;
        }
	}
	
	public function registerAction() {
		$form = new RegisterForm();
		$view = new ViewModel(array('form' =>$form));
		$view->setTemplate('users/users/register');
		return $view;
	}
	
    public function rgprocessAction() {
		
		if (!$this->request->isPost()) {
			return $this->redirect()->toRoute(NULL , array('controller' => 'users', 'action' => 'index'));
		}
		
		$post = $this->request->getPost();
		
		$form		= new RegisterForm();
		$inputFilter= new RegisterFilter();
        
		$form->setInputFilter($inputFilter);
		$form->setData($post);
				
		if (!$form->isValid()) {
			$model = new ViewModel(array('error' => true, 'form' =>$form));
			$model->setTemplate('users/users/register');
			return $model;
		} else {
			// Create user
            $user = new User();
            $user->exchangeArray($form->getData());
            $this->getUserTable()->saveUser($user);
			
			return $this->redirect()->toRoute(NULL , array( 'controller' => 'users', 'action' => 'confirm' ));
		}
		
	}
	
	public function confirmAction() {
		$view = new ViewModel();
		$view->setTemplate('users/users/rgsuccess');
		return $view;
	}
	
	public function viewAction() {
		
		$users1 = $this->getUserTable()->getUser(10);
        $users = $this->getUserTable()->allUser();
		
        echo "<pre>";
        print_r($users1);
        print_r($users);
        echo "</pre>";
        
		$view = new ViewModel(array('users' => $users));
		$view->setTemplate('users/users/view');
		return $view;
	}
	
	public function logoutAction() {
		
        $this->getAuthService()->clearIdentity();
        $this->flashmessenger()->addMessage("You've been logged out");
        return $this->redirect()->toRoute(NULL , array( 'controller' => 'users', 'action' => 'login' ));
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


