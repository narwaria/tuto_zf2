<?php
namespace ManageComponent\Model;

class Topic  {
    protected $inputFilter;
    
    public $topic_id;
    public $topic_name;
    public $client_id;
    public $created_by_user_id;
    public $created_date;

    public function exchangeArray($data) {
        $this->topic_id             = (isset($data['topic_id'])) ? $data['topic_id'] : null;
        $this->topic_name           = (isset($data['topic_name'])) ? $data['topic_name'] : null;
        $this->client_id            = (isset($data['client_id'])) ? $data['client_id'] : null;
        $this->created_by_user_id   = (isset($data['created_by_user_id'])) ? $data['created_by_user_id'] : null;
        $this->created_date         = (isset($data['created_date'])) ? $data['created_date'] : null;
    }
}