<?php
namespace ManageComponent\Model;

class Designation  {
    protected $inputFilter;
    
    public $desig_id;
    public $desig_name;
    public $dept_id;
    public $dept_name;
    public $client_id;
    public $created_by_user_id;
    public $created_date;

    public function exchangeArray($data) {
        $this->desig_id             = (isset($data['desig_id'])) ? $data['desig_id'] : null;
        $this->desig_name           = (isset($data['desig_name'])) ? $data['desig_name'] : null;
        $this->dept_id				= (isset($data['depts'])) ? $data['depts'] : null;
        $this->dept_name			= (isset($data['dept_name'])) ? $data['dept_name'] : 'N/A';
        $this->client_id        	= (isset($data['client_id'])) ? $data['client_id'] : null;
        $this->created_by_user_id	= (isset($data['created_by_user_id'])) ? $data['created_by_user_id'] : null;
        $this->created_date         = (isset($data['created_date'])) ? $data['created_date'] : null;
    }
}
