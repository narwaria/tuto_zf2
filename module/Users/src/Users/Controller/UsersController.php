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


//smtp mail 
use Zend\Mail\Message;
use Zend\Mail\Transport\Smtp as SmtpTransport;
use Zend\Mime\Message as MimeMessage;
use Zend\Mime\Part as MimePart;
use Zend\Mail\Transport\SmtpOptions;

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
    //echo $this->serverUrl($this->url('register')); die; 

    	if ($this->getAuthService()->hasIdentity()){
            return $this->redirect()->toRoute('dashboard', array('controller' => 'Dashboard','action' =>  'index'));
        } else {
        $this->layout('layout/login');
        $message = $this->flashmessenger()->getMessages();        
        $form = new LoginForm();
		$view = new ViewModel(array('form' =>$form, 'message'=>isset($message[0])?$message[0]:null));
		$view->setTemplate('users/users/login');
		return $view;
		}
	}
	
    public function loginProcessAction() { 

		$this->layout('layout/login');
        //form validating and initializing form post variales
        $post = $this->request->getPost();             
        if(!isset($post['email'])) {
			return $this->redirect()->toRoute(NULL , array('controller' => 'users', 'action' => 'login'));
		} else {
            $email      = $post['email'];
            $password   = $post['password'];
        }
               
		$this->getAuthService()->getAdapter()->setIdentity($email)->setCredential($password);
		$result = $this->getAuthService()->authenticate();
		//print_r($result);
        //validate login auth
		if (!$result->isValid()) {            
            $usersDetail = $this->getUserTable()->getUserByEmail($email);          
            if($usersDetail) {
                //update last login attemp                
                $dataArr = array('id'=>$usersDetail->id, 'logLastAttmp'=>date("Y-m-d H:i:s"), 'logFailedCount'=>$usersDetail->logFailedCount+1);
                $Config = $this->getServiceLocator()->get('Config');



                if( ($Config["interview_constants"]["default_login_attempts"] <= $usersDetail->logFailedCount) && ($usersDetail->status==1) ){

                	$dataArr["logFailedCount"]=0;
                	$this->renderer = $this->getServiceLocator()->get('ViewRenderer');  
					$mailcontent	=	$this->renderer->render('mails/AttemptResetPassword', null);
					//$encryptedresetlink = $this->encrypt(, ENCRYPTION_KEY);

					$encryptedresetlink		=	base64_encode("{$usersDetail->id}|".time());
					$ResetLink				=	"http://{$_SERVER["SERVER_NAME"]}/reset/{$encryptedresetlink}"; 
					$replace 				= 	array('#USERNAME#' => $usersDetail->name,"#RESETLINK#"=>$ResetLink); 
					$mailcontent			=	$this->str_replace_assoc($replace,$mailcontent);
    				$this->sendMail($usersDetail->email,"Reset password link",$mailcontent);
                }

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
			return $this->redirect()->toRoute('dashboard', array(
                        'controller' => 'Dashboard',
                        'action' =>  'index'
                            //'param' => 'updated/1'
                        ));
			
			//return $this->redirect()->toRoute(NULL , array('controller' => 'Dashboard', 'action' => 'index'));
		}
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
		
		$this->layout('layout/registeruser');
		$form = new RegisterForm();
		$view = new ViewModel(array('form' =>$form));
		$view->setTemplate('users/users/register');
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
			
			return $this->redirect()->toRoute(NULL , array( 'controller' => 'users', 'action' => 'confirm' ));
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
        $this->flashmessenger()->addMessage("You've been logged out");
        return $this->redirect()->toRoute(NULL , array( 'controller' => 'users', 'action' => 'login' ));
    }
    public function forgetAction(){
    	$this->layout('layout/dashboard');    	
    	$forgetpasswordForm = new ForgetpasswordForm();
    	$request = $this->getRequest();  
    	if($request->isPost()){
    		$post = $this->request->getPost();
    		$email=$post["email"];
    		$usersDetail = $this->getUserTable()->getUserByEmail($email);
    		if( isset($usersDetail->name) && ($usersDetail->name!="")){
    			
    			$this->renderer = $this->getServiceLocator()->get('ViewRenderer');  
				$mailcontent	=	$this->renderer->render('mails/ResetPassword', null);
					//$encryptedresetlink = $this->encrypt(, ENCRYPTION_KEY);

					$encryptedresetlink		=	base64_encode("{$usersDetail->id}|".time());
					$ResetLink				=	"http://{$_SERVER["SERVER_NAME"]}/reset/{$encryptedresetlink}"; 
					$replace 				= 	array('#USERNAME#' => $usersDetail->name,"#RESETLINK#"=>$ResetLink);
					$mailcontent			=	$this->str_replace_assoc($replace,$mailcontent);
    			$this->sendMail($usersDetail->email,"Please follow the instruction to Reset password",$mailcontent);
    		}    		
    	}
    	
    	
    	//
    	$view = new ViewModel(array("form"=>$forgetpasswordForm));
		$view->setTemplate('users/users/forget');
		return $view;
    }

    public function resetAction(){
    	$this->layout('layout/dashboard');
    	echo "alok"; die;
    	$view = new ViewModel();
		$view->setTemplate('users/users/rgsuccess');
		return $view;
    }
    
    public function getAuthService() {
		if (! $this->authservice) {
			$dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
			$dbTableAuthAdapter = new DbTableAuthAdapter($dbAdapter, 'user','email','password', 'MD5(?) AND status=1');
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


