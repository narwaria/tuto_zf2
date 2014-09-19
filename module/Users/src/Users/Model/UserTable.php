<?php
namespace Users\Model;

//use Zend\Db\Adapter\Adapter;
//use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\Adapter\Adapter;

use Zend\Db\Sql\Select;
use Zend\Db\Sql\Update;
use Zend\Db\Sql\Insert,Zend\Db\Sql\Delete;
use Zend\Db\Sql\Sql;
use Zend\Db\ResultSet\ResultSet;

class UserTable  extends AbstractTableGateway{
	
	protected $adapter;
	protected $token = 'token';
	protected $table = 'tbl_user';
	protected $user_permission=	"tbl_user_permission";
	
	public function __construct(Adapter $adapter) {
        $this->adapter = $adapter;        
    }

    public function fetchAll(Select $select = null) {
	if (null === $select)
	$select = new Select();
	$select->from($this->table);
	$resultSet = $this->selectWith($select);
	$resultSet->buffer();
	return $resultSet;
	}

    public function getMyUserList($params=array(),$client_id=null){
		$select = new Select();		
		$select->from($this->table);		
        $select->where->NEST->equalTo($this->table.'.client_id',$client_id)->UNNEST;
       
        if(isset($params["status"]) && $params["status"]!=""){
        	$status=($params["status"]=="active")?1:0;
        	$select->where->NEST->in($this->table.'.status',array($status))->UNNEST;
        }else{
        	$select->where->NEST->in($this->table.'.status',array(0,1))->UNNEST;
        }        
        $select->where->NEST->like('fname','%'.$params["search"].'%')->or->like('lname','%'.$params["search"].'%')->or->like('email','%'.$params["search"].'%')->UNNEST;
		$resultSet = $this->selectWith($select);				
		$resultSet->buffer();		
		return $resultSet;
    }
    public function createUser(User $user){
    	$data = array(
    		'fname'		=> $user->fname,
    		'lname'		=> $user->lname,
			'email'		=> $user->email,			
			'password'	=> $user->password,

			'designation'	=>	$user->designation,
			'organisation'	=>	$user->organisation,
			'organisation'	=>	$user->organisation,
			'phone'			=> $user->phone,

			'client_id'		=> $user->client_id,
			'status'		=> 0,
		);
    	$this->insert($data);
    }
    public function addUser(User $user){
    	$data = array(
    		'fname'		=> $user->fname,
    		'lname'		=> $user->lname,
			'email'		=> $user->email,			
			'password'	=> $user->password,

			'designation'	=>	$user->designation,
			'organisation'	=>	$user->organisation,
			'organisation'	=>	$user->organisation,
			'phone'			=> $user->phone,

			'client_id'		=> $user->client_id,
			'status'		=> $user->status,
		);
    	$this->insert($data);
    }
	
	public function updateUser(User $user) {
		$data = array(
			'fname'		=> $user->fname,
    		'lname'		=> $user->lname,
			'email'		=> $user->email,
			'status'	=> $user->status
		);		
		$user_id = (int)$user->user_id;		
		if ($user_id != 0) {
			$this->update($data, array('user_id' => $user_id));			
		}
	}

	public function resetpassword(User $user){
		$data = array(			
			'password'	=> $user->password,	
			'user_id'	=> $user->user_id,	
			'token'		=> null,		
		);
		if ($this->getUser($user->user_id)) {
				$this->update($data, array('user_id' => $user->user_id));
		} else {
				throw new \Exception('User ID does not exist');
		}		
	}
	
    public function updateLastLogin($user) {
        
		$data = array(
			'log_last_attmp' => $user['log_last_attmp'],
            'log_failed_count' => $user['log_failed_count'],
		);		
		$user_id = (int)$user['user_id'];		
        $this->update($data, array('user_id' => $user_id));		
	}
    
	public function getUser($user_id) {
		$user_id = (int) $user_id;
		$rowset = $this->select(array('user_id' => $user_id));
		$row = $rowset->current();
		if (!$row) {
			throw new \Exception("Could not find row $id");
		}
		return $row;
	}
	
	public function allUser() {
		$resultSet = $this->select();
		return $resultSet;
	}
	
	public function getUserByEmail($userEmail) {		
        $rowset = $this->select(array('email' => $userEmail));
        $row = $rowset->current();        
        return $row;
	}
	public function getActiveUserByEmail($userEmail) {	
        $rowset = $this->select(array('email' => $userEmail,"status"=>1));
        $row = $rowset->current();        
        return $row;
	}

	public function getActiveUserByUserid($user_id=null) {	
        $rowset = $this->select(array('user_id' => $user_id,"status"=>1));
        $row = $rowset->current();        
        return $row;
	}
	public function getInActiveUserByEmail($userEmail=null) {	
        $rowset = $this->select(array('email' => $userEmail,"status"=>0));
        $row = $rowset->current();        
        return $row;
	}
	public function getInActiveUserByUserid($user_id=null) {	
        $rowset = $this->select(array('user_id' => $user_id,"status"=>0));
        $row = $rowset->current();        
        return $row;
	}
	public function IsTokenAvalibleForUser($token=null,$user_id=null){	
        $rowset = $this->select(array('token' => $token,"user_id"=>$user_id));
        $row = $rowset->current();        
        return $row;

	}
	public function usertokeninsert($user_id=null,$token=null){
		$data = array(
			'token' => $token,		
		);			
        $this->update($data, array('user_id' => $user_id));
	}

	public function updateUserStatus($user_id=null){
		$data = array(
			'token' => NULL,
			'status' => 1,		
		);			
        $this->update($data, array('id' => (int)$user_id));
	}

	public function activateUserStatus($user_id=null){
		$data = array(
			'token' => NULL,
			'status' => 1,		
		);			
        $this->update($data, array('user_id' => (int)$user_id));
	}
	


	public function deleteUser(User $user) {
		$data = array(
			'token' => NULL,
			'status' => 2,		
		);			
        $this->update($data, array('user_id' => (int)$user->user_id));
	}

	public function CheckUserToken($token){	
        	$select = new Select();
            $select->from($this->table);           
            $select->where(array("token"=>$token));
            $resultSet 	= 	$this->selectWith($select);
            $rowset		=	$resultSet->current();           
            return $rowset;

	}

	public function createtoken($token=null){
		if($token) {
	        $sql        =   new Sql($this->adapter);
	        $insert     =   new Insert($this->token);
	        $insert->values(array('token'=>$token,'status'=>1));          
	        $selectString = $sql->getSqlStringForSqlObject($insert);           
	        $results = $this->adapter->query($selectString, Adapter::QUERY_MODE_EXECUTE);
        }   
    }
    public function update_token_status($tokenKey=null){
		if($tokenKey) {
	        $sql        =   new Sql($this->adapter);
	        $update     =   new Update($this->token);
	        $update->set(array('status'=>0));
	        $update->where(array("token"=>$tokenKey));
	       	$selectString = $sql->getSqlStringForSqlObject($update);
	        $results = $this->adapter->query($selectString, Adapter::QUERY_MODE_EXECUTE); 
        }   
    }


    public function clear_user_token($userid=NULL){
    	$data = array(
			'token' => null,		
		);			
        $this->update($data, array('id' => $userid));
    }

    public function tokenCheck($tokenKey=null){
		if($tokenKey) {
	        $sql        =   new Sql($this->adapter);
	        $select     =   new Select($this->token);
	        $select->where(array("token"=>$tokenKey));
	        $selectString = $sql->getSqlStringForSqlObject($select);
	        $results = $this->adapter->query($selectString, Adapter::QUERY_MODE_EXECUTE);   
	        $row = $results->current();	
        	return $row;      	
        }   
    }
    public function userPermissionSet($user_id,$permissionArray=array()){
    	if(count($permissionArray)!=0){
    		$this->deletePermissionByUserId($user_id);
    		foreach ($permissionArray as $key => $value) {
    			$sql        =   new Sql($this->adapter);
	            $insert     =   new Insert($this->user_permission);
	            $insert->values(array('user_id'=>(int)$user_id,'permission_id'=>(int)$value));             
	            $selectString = $sql->getSqlStringForSqlObject($insert);           
	            $results = $this->adapter->query($selectString, Adapter::QUERY_MODE_EXECUTE);
    		}
    	}
    }
    public function getUserPermission($user_id=0){
    	if($user_id!=0){
    		$sql        =   new Sql($this->adapter);        
	        $select 	= 	new Select($this->user_permission);	   
	        $select->columns(array("permission_id"));     
	        $select->where(array("user_id"=>$user_id));
	        $selectString = $sql->getSqlStringForSqlObject($select); 
	        $results = $this->adapter->query($selectString, Adapter::QUERY_MODE_EXECUTE);        
	        return $results->toArray();
    	}
    }
    public function deletePermissionByUserId($user_id=NULL){
        $sql        =   new Sql($this->adapter);
        $delete     =   new Delete($this->user_permission);
        $delete->where(array("user_id"=>$user_id));
        $selectString = $sql->getSqlStringForSqlObject($delete);
        $results = $this->adapter->query($selectString, Adapter::QUERY_MODE_EXECUTE);
    }
}
