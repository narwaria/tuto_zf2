<?php
namespace Users\Model;

class User {
	public $user_id;
	public $fname;
	public $lname;
	public $email;	
	public $password;

	public $designation;
	public $organisation;
	public $phone;
    public $log_last_attmp;
    public $log_failed_count;
    
    public $token;
    public $status;
    public $client_id;
	
	public function setPassword($clear_password) {
		return $this->password = md5($clear_password);
	}
	
	function exchangeArray($data) { 
	        
		$this->user_id 		= (isset($data['user_id'])) ? $data['user_id'] : null;
		$this->fname 		= (isset($data['fname'])) ? $data['fname'] : null;
		$this->lname 		= (isset($data['lname'])) ? $data['lname'] : null;
		$this->email 		= (isset($data['email'])) ? $data['email'] : null;
		$this->password 	= (isset($data["password"])) ? $this->setPassword($data["password"]) : null;

		$this->designation 		= (isset($data['designation'])) ? $data['designation'] : null;
		$this->organisation 	= (isset($data['organisation'])) ? $data['organisation'] : null;
		$this->phone 			= (isset($data['phone'])) ? $data['phone'] : null;
		$this->log_last_attmp 	= (isset($data['log_last_attmp'])) ? $data['log_last_attmp'] : null;
		$this->log_failed_count = (isset($data['log_failed_count'])) ? $data['log_failed_count'] : null;

		
		$this->token 		= (isset($data['token'])) ? $data['token'] : null;
        $this->status 		= (isset($data['status'])) ? $data['status'] : null;
        $this->client_id 	= (isset($data['client_id'])) ? $data['client_id'] : null;
        
	}
	
}
