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
//forget passowrd form
use Users\Form\ForgetpasswordForm;

use Users\Form\ResetForm;
use Users\Form\ResetFilter;


//smtp mail 
use Zend\Mail\Message;
use Zend\Mail\Transport\Smtp as SmtpTransport;
use Zend\Mime\Message as MimeMessage;
use Zend\Mime\Part as MimePart;
use Zend\Mail\Transport\SmtpOptions;

class UsersController extends AbstractActionController {

    protected $authservice;
    protected $UserTable;    
    
    public function getUserTable(){
        if (!$this->UserTable) {
            $sm = $this->getServiceLocator();
            $this->UserTable = $sm->get('Users\Model\UserTable');
        }
        return $this->UserTable;        
    }

    public function indexAction() {
    	if ($this->getAuthService()->hasIdentity()){
            return $this->redirect()->toRoute('dashboard', array('controller' => 'Dashboard','action' =>  'index'));
        } else {
        //return new ViewModel();
        $this->layout('layout/dashboard');
        $view = new ViewModel();
		$view->setTemplate('users/users/index');
		return $view;
		}		
    }
       
    public function loginAction() { 

    	/*
    	$this->flashMessenger()->setNamespace('error')
                 ->addMessage('Mail sending failed!');
        $this->flashMessenger()->setNamespace('warning')
                 ->addMessage('Mail  failed!'); */
        

    	if ($this->getAuthService()->hasIdentity()){
            return $this->redirect()->toRoute('dashboard', array('controller' => 'Dashboard','action' =>  'index'));
        }
        $authResponse = $this->getAuthService()->getStorage()->read();      

        $request = $this->getRequest();
		if ($request->isPost()) {
			$post = $this->request->getPost();			
			$email=	$post["email"];
			$usersDetail = $this->getUserTable()->getUserByEmail($email);			
	        if(isset($usersDetail->user_id) && ($usersDetail->status==0)){	        
	        	$this->flashMessenger()->setNamespace('warning')->addMessage('Your account is not activated!');
	        	return $this->redirect()->toRoute(NULL , array('controller' => 'users', 'action' => 'login'));
	        }	        
	        $this->getAuthService()->getAdapter()->setIdentity($request->getPost('email'))->setCredential($request->getPost('password'));
	        $result = $this->getAuthService()->authenticate();

	        //validate login auth
			if (!$result->isValid()) {             
		        if($usersDetail) {
		                //update last login attemp                
		                $dataArr = array('id'=>$usersDetail->user_id, 'logLastAttmp'=>date("Y-m-d H:i:s"), 'logFailedCount'=>$usersDetail->logFailedCount+1);
		                $Config = $this->getServiceLocator()->get('Config');
		                if( ($Config["interview_constants"]["default_login_attempts"] <= $usersDetail->logFailedCount) && ($usersDetail->status==1) ){
		                	$dataArr["logFailedCount"]=0;
		                	$this->renderer 		= 	$this->getServiceLocator()->get('ViewRenderer');  
							$mailcontent			=	$this->renderer->render('mails/AttemptResetPassword', null);
							$encryptedresetlink		=	base64_encode("{$usersDetail->id}|".time());
							$this->getUserTable()->usertokeninsert($usersDetail->id,$encryptedresetlink); //insert into token table  encryped data
							$ResetLink				=	"http://{$_SERVER["SERVER_NAME"]}/user/reset/{$encryptedresetlink}"; 
							$replace 				= 	array('#USERNAME#' => $usersDetail->name,"#RESETLINK#"=>$ResetLink); 
							$mailcontent			=	$this->str_replace_assoc($replace,$mailcontent);
		    				$this->sendMail($usersDetail->email,"Reset password link",$mailcontent);
		    				$this->flashmessenger()->addMessage("Email is send to reset password.");
		                }else{
		                	$this->flashMessenger()->setNamespace('warning')->addMessage('Invalid password');		                	
		                }
		                //$this->getUserTable()->updateLastLogin($dataArr);
		            } else {
		            	$this->flashMessenger()->setNamespace('warning')->addMessage('Your are not registered.');		               
		        }		            
					return $this->redirect()->toRoute(NULL , array('controller' => 'users', 'action' => 'login'));
			}else{
				//get user detail and assing value for global access
			$usersDetail = $this->getUserTable()->getUserByEmail($email);			
            
			$this->getAuthService()->getStorage()->write(array(
                'user_id' => $usersDetail->user_id,
                'fname'=> $usersDetail->fname,
                'lname'=> $usersDetail->lname,
				'email'=> $usersDetail->email,
                
                'designation'=> $usersDetail->designation,
                'organisation'=> $usersDetail->organisation,
                'phone'=> $usersDetail->phone,                
                'log_last_attmp'=> $usersDetail->log_last_attmp,
                'log_failed_count'=>$usersDetail->log_failed_count,
                'token'=>$usersDetail->token,
			));            
            //update last login attemp
            $logintdetailsArray = array('user_id'=>$usersDetail->user_id, 'log_last_attmp'=>date("Y-m-d H:i:s"), 'log_failed_count'=>'0');
            $this->getUserTable()->updateLastLogin($logintdetailsArray);            
			return $this->redirect()->toRoute('dashboard', array( 'controller' => 'Dashboard','action' =>  'index'));
			}
		}

        $this->layout('layout/login');        
        $form = new LoginForm();
		$view = new ViewModel(array('form' =>$form));
		$view->setTemplate('users/users/login');
		return $view;
		
	}
	
	public function loginsuccessAction() {
        $this->layout('layout/dashboard');
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
		$this->layout('layout/register');
		$RegisterUserForm = new RegisterForm();			
		// get form request methord.
		$request = $this->getRequest();
		if ($request->isPost()) {
			$post = $this->request->getPost();			
			$email=	$post["email"];

			$usersDetail 	= 		$this->getUserTable()->getUserByEmail($email);			
			$inputFilter 	= 		new RegisterFilter();        
			$RegisterUserForm->setInputFilter($inputFilter);
			$RegisterUserForm->setData($post);  			

	        if ( (!$RegisterUserForm->isValid()) || isset($usersDetail->user_id)) {
	        	if(isset($usersDetail->user_id))
	        		$RegisterUserForm->setMessages(array('email' => array( "This email is already registered with us.")));
	        	if($post->password !== $post->confirm_password)
	        		$RegisterUserForm->setMessages(array('confirm_password' => array( "Password not matched.")));
	        	//'confirm_password' => array( "Password not matched.")				
			} else {
					$user = new User();
		            $user->exchangeArray($RegisterUserForm->getData());
		            $this->getUserTable()->createUser($user);
		            $lastInsertUserID = $this->getUserTable()->lastInsertValue;
		       
		        $this->renderer = $this->getServiceLocator()->get('ViewRenderer');  
		      	$mailcontent			=	$this->renderer->render('mails/RegisterUser', null);
				$encryptedresetlink		=	base64_encode("{$lastInsertUserID}|".time());				
				$this->getUserTable()->usertokeninsert($lastInsertUserID,$encryptedresetlink); //insert into token table  encryped data
				$ResetLink				=	"http://{$_SERVER["SERVER_NAME"]}/user/activate/{$encryptedresetlink}"; 
				$replace 				= 	array('#USERNAME#' => $user->fname,"#CREATELINK#"=>$ResetLink);
				$mailcontent			=	$this->str_replace_assoc($replace,$mailcontent);
    			$this->sendMail($user->email,"Create Account successfully please follow the instruction to activate account",$mailcontent);
    			//die;
    			$this->flashmessenger()->addMessage("Email is send to your email id for activation.");
				return $this->redirect()->toRoute(NULL , array( 'controller' => 'users', 'action' => 'register' ));
			}
 		}
 		$model = new ViewModel(array('form' =>$RegisterUserForm));
		$model->setTemplate('users/users/register');
		return $model;       
	}

	public function manageAction(){
		$authResponse = $this->getAuthService()->getStorage()->read();
		$this->layout()->setVariable('auth',$authResponse);
		$this->layout('layout/dashboard');
		$view = new ViewModel();
		$view->setTemplate('users/users/manageusers');
		return $view;
	}
	
    public function rgprocessAction() {
		$this->layout('layout/registeruser');
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
            $this->renderer = $this->getServiceLocator()->get('ViewRenderer');  
			return $this->redirect()->toRoute(NULL , array( 'controller' => 'users', 'action' => 'confirm' ));
		}
		
	}
	public function activateAction(){
		$param 		= 	$this->params()->fromRoute('param') ? $this->params()->fromRoute('param') : '';
                $data		=	explode("|",base64_decode($param));                
		$userResult = $this->getUserTable()->getUser($data[0]);
		if($userResult->user_id){
			$userResult = $this->getUserTable()->activateUserStatus($userResult->user_id);
			$this->flashmessenger()->addMessage("Your account activated successfully");
			return $this->redirect()->toRoute(NULL , array('controller' => 'users', 'action' => 'login'));
		}else{
			$this->flashmessenger()->addMessage("Invalid token");
			return $this->redirect()->toRoute(NULL , array('controller' => 'users', 'action' => 'login'));
		}
	}

	
	public function confirmAction() {
		$this->layout('layout/dashboard');
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
        $this->flashMessenger()->setNamespace('info')->addMessage("You've been logged out");
        return $this->redirect()->toRoute(NULL , array( 'controller' => 'users', 'action' => 'login' ));
    }
    public function forgetAction(){    	
    	$this->layout('layout/login');    	
    	$forgetpasswordForm = new ForgetpasswordForm();
    	$request = $this->getRequest();  

    	if($request->isPost()){
    		$post = $this->request->getPost();
    		$email=	$post["email"];
    		$usersDetail = $this->getUserTable()->getUserByEmail($email);    		
    		if( isset($usersDetail->user_id) && ($usersDetail->user_id!="")){
    			
    			$this->renderer = $this->getServiceLocator()->get('ViewRenderer');  
				$mailcontent	=	$this->renderer->render('mails/ResetPassword', null);
					//$encryptedresetlink = $this->encrypt(, ENCRYPTION_KEY);
					$encryptedresetlink		=	base64_encode("{$usersDetail->user_id}|".time());					
					$this->getUserTable()->usertokeninsert($usersDetail->user_id,$encryptedresetlink); //insert into token table  encryped data
					$ResetLink				=	"http://{$_SERVER["SERVER_NAME"]}/user/reset/{$encryptedresetlink}"; 
					$replace 				= 	array('#USERNAME#' => $usersDetail->fname,"#RESETLINK#"=>$ResetLink);
					$mailcontent			=	$this->str_replace_assoc($replace,$mailcontent);
    				$this->sendMail($usersDetail->email,"Please follow the instruction to Reset password",$mailcontent);

    				$this->flashMessenger()->setNamespace('info')->addMessage("Reset password link is send to your email id");
        			return $this->redirect()->toRoute(NULL , array( 'controller' => 'users', 'action' => 'forget' ));
    		}else{
    			$this->flashMessenger()->setNamespace('warning')->addMessage("Your email is not registerd with us");
        		return $this->redirect()->toRoute(NULL , array( 'controller' => 'users', 'action' => 'forget' ));    			
    		}
    	}

    	$view = new ViewModel(array("form"=>$forgetpasswordForm));
		$view->setTemplate('users/users/forget');
		return $view;
    }

    public function resetAction(){
    	$this->layout('layout/register'); 
    	$ResetForm 	=	new ResetForm();
    	$param 		= 	$this->params()->fromRoute('param') ? $this->params()->fromRoute('param') : '';

    	$request 		= 	$this->getRequest(); 
    	$resultToken	=	$this->getUserTable()->CheckUserToken($param);
    	if(!$resultToken->user_id) {
    		$this->flashMessenger()->setNamespace('warning')->addMessage("Invalid token");    		
        	return $this->redirect()->toRoute(NULL , array( 'controller' => 'users', 'action' => 'login' )); 
    	}
    	if($resultToken->status==0) {    		
    		$this->flashMessenger()->setNamespace('info')->addMessage("User is not activated");
        	return $this->redirect()->toRoute(NULL , array( 'controller' => 'users', 'action' => 'login' )); 
    	}
    	if($resultToken->status==2) {
    		$this->flashmessenger()->addMessage("User is blocked by admin");
        	return $this->redirect()->toRoute(NULL , array( 'controller' => 'users', 'action' => 'login' )); 
    	}
    	
    	if($request->isPost()){
    		$post 				= 	$this->request->getPost();
    		$password 			=	$post->password;
    		$confirm_password 	=	$post->confirm_password;
    		$Userdata			=	explode("|",base64_decode($param));   		
    		$post = $this->request->getPost();		
			$inputFilter= new ResetFilter();
	        
			$ResetForm->setInputFilter($inputFilter);
			$ResetForm->setData($post);				
			if (!$ResetForm->isValid()) {
				
			} else {			
				$post->user_id=$Userdata[0];			
	            $user = new User();
	            $user->exchangeArray($post);
	            // Update user Password
	            $this->getUserTable()->resetpassword($user);           	            
	            $this->flashMessenger()->setNamespace('info')->addMessage("Your password is updated successfully");
        		return $this->redirect()->toRoute(NULL , array( 'controller' => 'users', 'action' => 'login' ));        			
			}

    	}

    	$view 		= 	new ViewModel(array("form"=>$ResetForm,"param"=>$param)); 
		$view->setTemplate('users/users/resetpassword');
		return $view;
    }
    public function getTopicTable()
    {       
        if (!$this->UserTable) {            
            $sm = $this->getServiceLocator();          
            $this->UserTable = $sm->get('Users\Model\UserTable');
        }        
        return $this->UserTable;
    }
    public function getAuthService() {
		if (! $this->authservice) {
			$dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
			$dbTableAuthAdapter = new DbTableAuthAdapter($dbAdapter, 'tbl_user','email','password', 'MD5(?) AND status=1');
			$authService = new AuthenticationService();
			$authService->setAdapter($dbTableAuthAdapter);
			$this->authservice = $authService;
		}
		return $this->authservice;
	}

	private function sendMail($to,$subject,$body){
    	$message = new Message();
		$message->addTo($to)
				->addFrom('alok1606@gmail.com')
				->setSubject($subject);
		// Setup SMTP transport using LOGIN authentication
		$transport 	= new SmtpTransport();
		$options 	= new SmtpOptions(array(
							'host' => 'smtp.gmail.com',
							'connection_class' => 'login',
							'connection_config' => array('ssl' => 'tls',
														'username' => 'alok1606@gmail.com',
														'password' => 'narwaria'),
							'port' => 587,
					));		 
		$html = new MimePart($body);
		$html->type = "text/html";		 
		$body = new MimeMessage();
		$body->addPart($html);		 
		$message->setBody($body);		 
		$transport->setOptions($options);
		$transport->send($message);
   	}

	private function str_replace_assoc(array $replace, $subject) {
		   return str_replace(array_keys($replace), array_values($replace), $subject);   
	} 

}


