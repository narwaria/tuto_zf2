<?php

namespace Users\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use Users\Model\User;

use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Adapter\DbTable as DbTableAuthAdapter;


use Users\Form\AddUserForm;
use Users\Form\AddUserFilter;


use Users\Form\EditUserForm;
use Users\Form\EditUserFilter;

use Users\Form\DeleteUserForm;

use Users\Form\SearchUserForm;

use Zend\Db\Sql\Select;
use Zend\Paginator\Paginator;
use Zend\Paginator\Adapter\Iterator as paginatorIterator;

class UserManagerController extends AbstractActionController
{
    protected $authservice;
    protected $userTable;

    public function indexAction()
    {
        $this->layout('layout/dashboard');
        $authResponse = $this->getAuthService()->getStorage()->read();                
        $this->layout()->setVariable('auth',$authResponse);
        $this->userTable    =   $this->getServiceLocator()->get('UserTable');
        $config = $this->getServiceLocator()->get('Config');

        $pagin      =   $config["interview_constants"]["default_pager_elements"];
        $page       =   (int)$this->params()->fromQuery('page', 1);
        $search     =   $this->params()->fromQuery('search', "");
        $status     =   $this->params()->fromQuery('status', "");
        $params     =   array("search"=>$search,"status"=>(string)$status);

        $SearchUserForm =   new SearchUserForm();
        $SearchUserForm->get('search')->setValue($search);
        $SearchUserForm->get('status')->setValue($status);

        $querydata  =   http_build_query(array_filter($params));

        $userlist = $this->userTable->getMyUserList($params,$authResponse["user_id"]); 
        $itemsPerPage =2;
        $userlist->current();
        $paginator = new Paginator(new paginatorIterator($userlist));
        $paginator->setCurrentPageNumber($page)
        ->setItemCountPerPage($itemsPerPage)
        ->setPageRange(7);
        return new ViewModel(array('paginator' => $paginator,"auth"=>$authResponse,"querydata"=>$querydata,"form"=>$SearchUserForm));
              
    }

    public function editAction()
    {
        $user_id   = (int)$this->params()->fromRoute('user_id', 0);
        if($user_id==0){    return $this->redirect()->toRoute("manageuser" , array('controller' => 'users', 'action' => 'index'));  }

        $this->userTable    =   $this->getServiceLocator()->get('UserTable');       
        $authResponse       =   $this->getAuthService()->getStorage()->read();       
        $this->layout()->setVariable('auth',$authResponse);
        $this->layout('layout/dashboard');

        $userDetail=$this->userTable->getUser($user_id);
        $userPermissions=$this->userTable->getUserPermission($user_id);
        $permissions = array();
        foreach ($userPermissions as $key => $value) {
                $permissions[] =   (int)$value["permission_id"];
        }        
        $Config = $this->getServiceLocator()->get('Config');
        $editUserForm = new EditUserForm();
        $request    = $this->getRequest();
        if ($request->isPost()) {
            $post       =       $this->request->getPost();
            $getUser    =       $this->userTable->getUserByEmail($post->email);
            if($post->password != $post->confirm_password){
                    $editUserForm->setMessages(array('confirm_password' => array( "Password not matched."))); 
            } else if(isset($getUser->user_id) && $post->email!=$userDetail->email) {
                    $editUserForm->setMessages(array('email' => array( "This email is already registered with us.")));            
            } else {               
                    $edituserFilter    = new EditUserFilter();
                    $editUserForm->setInputFilter($edituserFilter);
                    $editUserForm->setData($request->getPost());               
                if ($editUserForm->isValid()) {
                    $user = new User();
                    $userPostFormData=$editUserForm->getData();
                    $usersResultArray = array_merge($userPostFormData, array("user_id"=>$user_id));               
                    $user->exchangeArray($usersResultArray);
                    $this->userTable->updateUser($user);
                    $this->userTable->userPermissionSet($user_id,$userPostFormData["permission"]); //insert user permission table 
                    $this->flashMessenger()->setNamespace('info')->addMessage('Account updated successfully');
                    return $this->redirect()->toRoute('manageuser' , array( 'controller' => 'users', 'action' => 'edit','user_id'=>$user_id ));              
                }
            }                        
        }
        $editUserForm->bind($userDetail);       
        $editUserForm->get('permission')->setValue($permissions);
        $editUserForm->get('permission')->setValueOptions($Config["user_permission"]);
        $editUserForm->get('password')->setValue("");
        $editUserForm->get('confirm_password')->setValue("");
        $model = new ViewModel(array('form' =>$editUserForm,"user_id"=>$user_id));       
        return $model;
    }

    public function deleteAction()    {
        $user_id   = (int)$this->params()->fromRoute('user_id', 0);
        if($user_id==0){    return $this->redirect()->toRoute("manageuser" , array('controller' => 'users', 'action' => 'index'));  }

        $this->userTable    =   $this->getServiceLocator()->get('UserTable');       
        $authResponse       =   $this->getAuthService()->getStorage()->read();       
        $this->layout()->setVariable('auth',$authResponse);      
        $this->layout('layout/dashboard');        
        $userDetail=$this->userTable->getUser($user_id);
        $DeleteUserForm=  new DeleteUserForm();
        $request    = $this->getRequest();
        if ($request->isPost()) {
            $post       =       $this->request->getPost();
            if($post->user_id){ 
                    $user = new User();
                    $user->exchangeArray($post);
                    $this->userTable->deleteUser($user);                   
                    $this->flashMessenger()->setNamespace('info')->addMessage("User {$userDetail->fname} deleted successfully");
                    return $this->redirect()->toRoute('manageuser' , array( 'controller' => 'users', 'action' => 'index' ));              
            }                        
        }
        $DeleteUserForm->bind($userDetail);
        $model = new ViewModel(array('form' =>$DeleteUserForm,'user_id'=>$user_id));       
        return $model;
    }

    public function adduserAction()
    {
        $this->userTable    =   $this->getServiceLocator()->get('UserTable');       
        $authResponse       =   $this->getAuthService()->getStorage()->read();       
        $this->layout()->setVariable('auth',$authResponse);
        $Config = $this->getServiceLocator()->get('Config');
        $this->layout('layout/dashboard');
        $AddUserForm = new AddUserForm();
        $AddUserForm->get('permission')->setValueOptions($Config["user_permission"]);
        $this->layout()->setVariable('auth',$authResponse);

        // get form request methord.
        $request = $this->getRequest();        
        if ($request->isPost()) {
            $post           =       $this->request->getPost();          
            $email          =       $post["email"];
            $usersDetail    =       $this->userTable->getActiveUserByEmail($email);          
            $inputFilter    =       new AddUserFilter();        
            $AddUserForm->setInputFilter($inputFilter);
            $AddUserForm->setData($post);
            
            if ( (!$AddUserForm->isValid()) || isset($usersDetail->user_id)) {
                if(isset($usersDetail->user_id))
                    $AddUserForm->setMessages(array('email' => array( "This email is already registered with us.")));
                if($post->password !== $post->confirm_password)
                    $AddUserForm->setMessages(array('confirm_password' => array( "Password not matched.")));
            } else {
                    $user = new User();
                    $FormUserData    =   $AddUserForm->getData();                    
                    $result = array_merge($FormUserData, array("client_id"=>$authResponse["user_id"]));                  
                    $user->exchangeArray($result);                   
                    $this->userTable->addUser($user);
                    $lastInsertUserID = $this->userTable->lastInsertValue;
               
                $this->userTable->userPermissionSet($lastInsertUserID,$post["permission"]); //insert user permission table 
                $this->renderer         =   $this->getServiceLocator()->get('ViewRenderer');  
                $mailcontent            =   $this->renderer->render('mails/AddUser', null);
                $encryptedresetlink     =   base64_encode("{$lastInsertUserID}|".time());               
                $this->userTable->usertokeninsert($lastInsertUserID,$encryptedresetlink); //insert into token table  encryped data
                

                $ResetLink              =   "http://{$_SERVER["SERVER_NAME"]}/user/activate/{$encryptedresetlink}"; 
                $tokenKeyValues         =   array('#USERNAME#' => $user->fname,"#CREATELINK#"=>$ResetLink);             
                $msgSubject =   "Create Account successfully please follow the instruction to activate account";
                $this->SendMail()->SendMailSmtp($user->email,$msgSubject,$mailcontent,$tokenKeyValues);
                $this->flashMessenger()->setNamespace('info')->addMessage('Email is send to your email id for activation');             
                return $this->redirect()->toRoute("manageuser" , array( 'controller' => 'users', 'action' => 'adduser' ));
            }
        }
        $model = new ViewModel(array('form' =>$AddUserForm));       
        return $model;

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
            $dbTableAuthAdapter = new DbTableAuthAdapter($dbAdapter, 'user','email','password', 'MD5(?) AND status=1');
            $authService = new AuthenticationService();
            $authService->setAdapter($dbTableAuthAdapter);
            $this->authservice = $authService;
        }
        return $this->authservice;
    }


}

