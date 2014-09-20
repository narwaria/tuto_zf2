<?php
namespace ManageComponent\Model;

class Skill  {
    protected $inputFilter;
    
    public $skill_id;
    public $skill_name;
    public $client_id;
    public $created_by_user_id;
    public $created_date;

    public function exchangeArray($data) {
        $this->skill_id             = (isset($data['skill_id'])) ? $data['skill_id'] : null;
        $this->skill_name           = (isset($data['skill_name'])) ? $data['skill_name'] : null;
        $this->client_id            = (isset($data['client_id'])) ? $data['client_id'] : null;
        $this->created_by_user_id   = (isset($data['created_by_user_id'])) ? $data['created_by_user_id'] : null;
        $this->created_date         = (isset($data['created_date'])) ? $data['created_date'] : null;
    }
}
