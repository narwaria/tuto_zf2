<?php
namespace Users\Model;

class User {
	public $id;
	public $name;
	public $email;
	public $password;
	public $address;
	public $phone;
    public $logLastAttmp;
    public $logFailedCount;
	
	public function setPassword($clear_password) {
		return $this->password = md5($clear_password);
	}
	
	function exchangeArray($data) { 
        
		$this->id = (isset($data['id'])) ? $data['id'] : null;
		$this->name = (isset($data['name'])) ? $data['name'] : null;
		$this->email = (isset($data['email'])) ? $data['email'] : null;
		$this->password = (isset($data["password"])) ? $this->setPassword($data["password"]) : null;
		$this->address = (isset($data['address'])) ? $data['address'] : null;
		$this->phone = (isset($data['phone'])) ? $data['phone'] : null;
		$this->logLastAttmp = (isset($data['logLastAttmp'])) ? $data['logLastAttmp'] : null;
        $this->logFailedCount = (isset($data['logFailedCount'])) ? $data['logFailedCount'] : null;
        
	}
	
}
