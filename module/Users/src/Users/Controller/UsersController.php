<?php

namespace Users\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use Zend\View\Model\JsonModel;

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


class UsersController extends AbstractActionController {

    protected $authservice;
    //protected $UserTable;
    protected $userTable;
    
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
    
   public function refreshAction(){
        $form = new RegisterForm();
        $captcha = $form->get('captcha')->getCaptcha();
        $data = array();
        $data['id']  = $captcha->generate();
        $data['src'] = $captcha->getImgUrl() .
                       $captcha->getId() .
                       $captcha->getSuffix();       
        $json = new JsonModel($data);
        return $json;
    } 
       
    public function loginAction() { 
    	if ($this->getAuthService()->hasIdentity()){
            return $this->redirect()->toRoute('dashboard', array('controller' => 'Dashboard','action' =>  'index'));
        }
        $authResponse = $this->getAuthService()->getStorage()->read();             
        $request = $this->getRequest();
		if ($request->isPost()) {
			$this->userTable    =   $this->getServiceLocator()->get('UserTable');
			$post = $this->request->getPost();			
		
			$inavtiveUser = $this->userTable->getInActiveUserByEmail($post->email);			
	        if(isset($inavtiveUser->user_id) && ($inavtiveUser->status==0)){	        
	        	$this->flashMessenger()->setNamespace('warning')->addMessage('Your account is not activated!');
	        	return $this->redirect()->toRoute(NULL , array('controller' => 'users', 'action' => 'login'));
	        }
	        $usersDetail = $this->userTable->getActiveUserByEmail($post->email);
	        $this->getAuthService()->getAdapter()->setIdentity($request->getPost('email'))->setCredential($request->getPost('password'));
	        $result = $this->getAuthService()->authenticate();

	        //validate login auth
			if (!$result->isValid()) {             
		        if(isset($usersDetail->user_id)) {
		                //update last login attemp                
		                $dataArr = array('user_id'=>$usersDetail->user_id, 'log_last_attmp'=>date("Y-m-d H:i:s"), 'log_failed_count'=>$usersDetail->log_failed_count+1);
		                $Config = $this->getServiceLocator()->get('Config');
		                if( ($Config["interview_constants"]["default_login_attempts"] <= $usersDetail->log_failed_count) && ($usersDetail->status==1) ){
		                	$dataArr["log_failed_count"]=0;
		                	$this->renderer 		= 	$this->getServiceLocator()->get('ViewRenderer');  
							$mailcontent			=	$this->renderer->render('mails/AttemptResetPassword', null);
							$encryptedresetlink		=	base64_encode("{$usersDetail->id}|".time());
							$this->userTable->usertokeninsert($usersDetail->user_id,$encryptedresetlink); //insert into token table  encryped data
							$ResetLink				=	"http://{$_SERVER["SERVER_NAME"]}/user/reset/{$encryptedresetlink}"; 


							$tokenKeyValues			= 	array('#USERNAME#' => $usersDetail->name,"#RESETLINK#"=>$ResetLink);							
		    				$msgSubject 			=	"Reset password link";
		    				$this->SendMail()->SendMailSmtp($usersDetail->email,$msgSubject,$mailcontent,$tokenKeyValues);
		    				$this->flashMessenger()->setNamespace('info')->addMessage('Email is send to reset password');		    				
		                }else{
		                	$this->flashMessenger()->setNamespace('warning')->addMessage('Invalid password');		                	
		                }
		                $this->userTable->updateLastLogin($dataArr);
		            } else {
		            	$this->flashMessenger()->setNamespace('warning')->addMessage('Your are not registered with us');		               
		        	}		            
					return $this->redirect()->toRoute(NULL , array('controller' => 'users', 'action' => 'login'));
			}else{
			
			//get user detail and assing value for global access
			$usersDetail = $this->userTable->getActiveUserByEmail($post->email);
			$userPermission = $this->userTable->getUserPermission($usersDetail->user_id);			
			$permissions = array();
			foreach ($userPermission as $key => $value) {
            	$permissions[] =   (int)$value["permission_id"];
       		}       		
			$this->getAuthService()->getStorage()->write(array(
                'user_id'			=> $usersDetail->user_id,
                'fname'				=> $usersDetail->fname,
                'lname'				=> $usersDetail->lname,
				'email'				=> $usersDetail->email,
                
                'designation'		=> $usersDetail->designation,
                'organisation'		=> $usersDetail->organisation,
                'phone'				=> $usersDetail->phone,
                'client_id'			=>(int)$usersDetail->client_id,
                'UserPermissions'	=> $permissions,
			));            
            //update last login attemp
            $logintdetailsArray = array('user_id'=>$usersDetail->user_id, 'log_last_attmp'=>date("Y-m-d H:i:s"), 'log_failed_count'=>'0');
            $this->userTable->updateLastLogin($logintdetailsArray);            
			return $this->redirect()->toRoute('dashboard', array( 'controller' => 'Dashboard','action' =>  'index'));
			}
		}

        $this->layout('layout/login');        
        $form = new LoginForm();
		$view = new ViewModel(array('form' =>$form));
		$view->setTemplate('users/users/login');
		return $view;
		
	}
	
	public function registerAction() {
		$this->userTable    =   $this->getServiceLocator()->get('UserTable');
		$this->layout('layout/register');
		$RegisterUserForm = new RegisterForm();			
		// get form request methord.
		$request = $this->getRequest();
		if ($request->isPost()) {
			$post = $this->request->getPost();			
			$email=	$post["email"];

			$usersDetail 	= 		$this->userTable->getActiveUserByEmail($post->email);			
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
				$tokenKeyValues 		= 	array('#USERNAME#' => $user->fname,"#CREATELINK#"=>$ResetLink);				
    			$msgSubject	=	"Welcome to Interview Organiser";
    			$this->SendMail()->SendMailSmtp($user->email,$msgSubject,$mailcontent,$tokenKeyValues);
    			$this->flashMessenger()->setNamespace('info')->addMessage('Email is send to your email id for activation');
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
   
	public function activateAction(){
		$this->userTable    =   $this->getServiceLocator()->get('UserTable');
		$token 				= 	$this->params()->fromRoute('param') ? $this->params()->fromRoute('param') : '';
		list($user_id,$datetime)	=	explode("|",base64_decode(trim($token)));		
		$isValidinterval	=	$this->UsersCommonFuncions()->CheckDatetimeRange($datetime);
		if(!$isValidinterval){		
			$this->flashMessenger()->setNamespace('info')->addMessage('Your activation link get expire');			
			return $this->redirect()->toRoute(NULL , array('controller' => 'users', 'action' => 'login'));
		}
 		$userResult 		= 	$this->userTable->IsTokenAvalibleForUser($token,$user_id);
		if($userResult->user_id){
			$userResult 	= $this->userTable->activateUserStatus($userResult->user_id);
			$this->flashMessenger()->setNamespace('info')->addMessage('Your account activated successfully');			
			return $this->redirect()->toRoute(NULL , array('controller' => 'users', 'action' => 'login'));
		}else{			
			$this->flashMessenger()->setNamespace('warning')->addMessage('Invalid token');
			return $this->redirect()->toRoute(NULL , array('controller' => 'users', 'action' => 'login'));
		}
	}	
	public function confirmAction() {
		$this->layout('layout/dashboard');
		$view = new ViewModel();
		$view->setTemplate('users/users/rgsuccess');
		return $view;
	}	
	public function logoutAction() {		
        $this->getAuthService()->clearIdentity();        
        $this->flashMessenger()->setNamespace('info')->addMessage("You've been logged out");
        return $this->redirect()->toRoute(NULL , array( 'controller' => 'users', 'action' => 'login' ));
    }
    public function forgetAction(){  
    	$this->userTable    =   $this->getServiceLocator()->get('UserTable');  	
    	$this->layout('layout/login');    	
    	$forgetpasswordForm = new ForgetpasswordForm();
    	$request = $this->getRequest();  

    	if($request->isPost()){
    		$post = $this->request->getPost();
    		$inavtiveUser = $this->userTable->getInActiveUserByEmail($post->email);			
	        if(isset($inavtiveUser->user_id) && ($inavtiveUser->status==0)){	        
	        	$this->flashMessenger()->setNamespace('warning')->addMessage('Please activate your account first');
	        	return $this->redirect()->toRoute(NULL , array('controller' => 'users', 'action' => 'forget'));
	        }

    		$usersDetail = $this->userTable->getActiveUserByEmail($post->email);    		
    		if( isset($usersDetail->user_id) && ($usersDetail->user_id!="")){    			
	    			$this->renderer = $this->getServiceLocator()->get('ViewRenderer');  
					$mailcontent	=	$this->renderer->render('mails/ResetPassword', null);
					//$encryptedresetlink = $this->encrypt(, ENCRYPTION_KEY);
					$encryptedresetlink		=	base64_encode("{$usersDetail->user_id}|".time());					
					$this->userTable->usertokeninsert($usersDetail->user_id,$encryptedresetlink); //insert into token table  encryped data
					$ResetLink				=	"http://{$_SERVER["SERVER_NAME"]}/user/reset/{$encryptedresetlink}"; 
					$tokenKeyValues			= 	array('#USERNAME#' => $usersDetail->fname,"#RESETLINK#"=>$ResetLink);					
    				$subject				=	"Interview Organiser - Password Reset";
    				$this->SendMail()->SendMailSmtp($usersDetail->email,$subject,$mailcontent,$tokenKeyValues);

    				$this->flashMessenger()->setNamespace('info')->addMessage("We have mailed your Password Reset link. Please check your mail box");
        			return $this->redirect()->toRoute(NULL , array( 'controller' => 'users', 'action' => 'forget' ));
    		}else{
    			$this->flashMessenger()->setNamespace('danger')->addMessage("Your email is not registerd with us");
        		return $this->redirect()->toRoute(NULL , array( 'controller' => 'users', 'action' => 'forget' ));    			
    		}
    	}

    	$view = new ViewModel(array("form"=>$forgetpasswordForm));
		$view->setTemplate('users/users/forget');
		return $view;
    }

    public function resetAction(){
    	$this->userTable    =   $this->getServiceLocator()->get('UserTable');
    	$this->layout('layout/register'); 
    	$ResetForm 	=	new ResetForm();    	

    	$token 				= 	$this->params()->fromRoute('param') ? $this->params()->fromRoute('param') : '';
		list($user_id,$datetime)	=	explode("|",base64_decode(trim($token)));
		$isValidinterval	=	$this->UsersCommonFuncions()->CheckDatetimeRange($datetime);
		if(!$isValidinterval){		
			$this->flashMessenger()->setNamespace('info')->addMessage('Your link get expired');			
			return $this->redirect()->toRoute(NULL , array('controller' => 'users', 'action' => 'login'));
		}

    	$request 		= 	$this->getRequest(); 
    	$resultToken	=	$this->userTable->IsTokenAvalibleForUser($token,$user_id);
    	if(!$resultToken->user_id) {
    		$this->flashMessenger()->setNamespace('warning')->addMessage("Invalid token");    		
        	return $this->redirect()->toRoute(NULL , array( 'controller' => 'users', 'action' => 'login' )); 
    	}
    	if($resultToken->status==0) {    		
    		$this->flashMessenger()->setNamespace('info')->addMessage("User is not activated, So you can not reset password");
        	return $this->redirect()->toRoute(NULL , array( 'controller' => 'users', 'action' => 'login' )); 
    	}
    	if($request->isPost()){
    		$post 				= 	$this->request->getPost();
    		$password 			=	$post->password;
    		$confirm_password 	=	$post->confirm_password;    		   		
    		$post = $this->request->getPost();		
			$inputFilter= new ResetFilter();
	        
			$ResetForm->setInputFilter($inputFilter);
			$ResetForm->setData($post);				
			if (!$ResetForm->isValid()) {
				
			} else {			
				$post->user_id=$user_id;			
	            $user = new User();
	            $user->exchangeArray($post);
	            // Update user Password
	            $this->getUserTable()->resetpassword($user);           	            
	            $this->flashMessenger()->setNamespace('info')->addMessage("Your password is updated successfully");
        		return $this->redirect()->toRoute(NULL , array( 'controller' => 'users', 'action' => 'login' ));        			
			}

    	}
    	$view 		= 	new ViewModel(array("form"=>$ResetForm,"param"=>$token)); 
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

}