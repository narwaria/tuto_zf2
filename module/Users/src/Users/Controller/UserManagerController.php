<?php
namespace Users\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Adapter\DbTable as DbTableAuthAdapter;

use Users\Form\AddUserForm;
use Users\Form\AddUserFilter;

use Users\Model\User;

//smtp mail 
use Zend\Mail\Message;
use Zend\Mail\Transport\Smtp as SmtpTransport;
use Zend\Mime\Message as MimeMessage;
use Zend\Mime\Part as MimePart;
use Zend\Mail\Transport\SmtpOptions;

class UserManagerController extends AbstractActionController
{
    protected $authservice;
    protected $userTable;
    public function indexAction()
    {
        $authResponse = $this->getAuthService()->getStorage()->read();
        
        $this->layout('layout/dashboard');
        $this->layout()->setVariable('auth',$authResponse);
        $view = new ViewModel();
        //$view->setTemplate('users/users/rgsuccess');
        return $view;        
    }

    public function editAction()
    {
        return new ViewModel();
    }

    public function deleteAction()
    {
        return new ViewModel();
    }
    public function adduserAction()
    {
        $authResponse = $this->getAuthService()->getStorage()->read();        
        $AddUserForm    =   new AddUserForm();
        $request = $this->getRequest();
	if ($request->isPost()) {
                        $this->userTable	=       $this->getServiceLocator()->get('UsersTable');                         
			$post = $this->request->getPost();			
			$email=	$post["email"];                        
			$usersDetail            = 	$this->userTable->getUserByEmail($email);			
			$inputFilter            =	new AddUserFilter();        
			$AddUserForm->setInputFilter($inputFilter);
			$AddUserForm->setData($post);  			

	        if ( (!$AddUserForm->isValid()) || isset($usersDetail->user_id)) {
	        	if(isset($usersDetail->user_id))
	        		$AddUserForm->setMessages(array('email' => array( "This email is already registered with us.")));
	        	if($post->password !== $post->confirm_password)
	        		$AddUserForm->setMessages(array('confirm_password' => array( "Password not matched.")));	        	
			} else {                            
                            $user = new User();                            
		            $user->exchangeArray($AddUserForm->getData());                            
                            $user->client_id    =       $authResponse["user_id"];
		            $this->userTable->createUser($user);
		            $lastInsertUserID = $this->userTable->lastInsertValue;		       
                                $this->renderer = $this->getServiceLocator()->get('ViewRenderer');  
                                $mailcontent			=	$this->renderer->render('mails/RegisterUser', null);
				$encryptedresetlink		=	base64_encode("{$lastInsertUserID}|".time());				
				$this->userTable->usertokeninsert($lastInsertUserID,$encryptedresetlink); //insert into token table  encryped data
				$ResetLink				=	"http://{$_SERVER["SERVER_NAME"]}/user/activate/{$encryptedresetlink}"; 
				$replace 				= 	array('#USERNAME#' => $user->fname,"#CREATELINK#"=>$ResetLink);
				$mailcontent			=	$this->str_replace_assoc($replace,$mailcontent);
                                $this->sendMail('aloknarwaria@gmail.com',"{$user->email}Create Account successfully please follow the instruction to activate account",$mailcontent);
    			
                                $this->flashmessenger()->addMessage("Email is send to your email id for activation.");
				return $this->redirect()->toRoute(NULL , array( 'controller' => 'users', 'action' => 'adduser' ));
			}
 	}
        $this->layout()->setVariable('auth',$authResponse);
        $this->layout('layout/dashboard');
        $view = new ViewModel(array('form'=>$AddUserForm));
        return $view;   
    }

    public function addAction()
    {
        $authResponse = $this->getAuthService()->getStorage()->read();
        $this->layout()->setVariable('auth',$authResponse);
        $this->layout('layout/dashboard');
        $view = new ViewModel();
        //$view->setTemplate('users/users/rgsuccess');
        return $view;   
    }

    private function getAuthService() {
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

