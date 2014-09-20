<?php
namespace ManageComponent\Model;

class Department  {
    protected $inputFilter;
    
    public $dept_id;
    public $dept_name;
    public $client_id;
    public $created_by_user_id;
    public $created_date;

    public function exchangeArray($data) {
        $this->dept_id				= (isset($data['dept_id'])) ? $data['dept_id'] : null;
        $this->dept_name			= (isset($data['dept_name'])) ? $data['dept_name'] : null;
        $this->client_id            = (isset($data['client_id'])) ? $data['client_id'] : null;
        $this->created_by_user_id   = (isset($data['created_by_user_id'])) ? $data['created_by_user_id'] : null;
        $this->created_date         = (isset($data['created_date'])) ? $data['created_date'] : null;
    }
}
