<?php
// module/ManageComponent/src/ManageComponent/Form/TopicForm.php:
namespace ManageComponent\Form;

use Zend\Form\Form;

class TopicForm extends Form {
    public function __construct($name = null)
    {
        // we want to ignore the name passed
        parent::__construct('topic');
        $this->setAttribute('method', 'post');
        $this->add(array(
            'name' => 'topic_id',
            'attributes' => array(
                'type'  => 'hidden',
                
            ),
        ));
        $this->add(array(
            'name' => 'topic_name',
            'attributes' => array(
                'type'  => 'text',
                'class'=>'form-control',
                'placeholder'=>"Topic Name"
            ),
            'options' => array(
                //'label' => 'Topic Name',
            ),
        ));         
        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type'  => 'submit',
                'value' => 'Create Topic',
                'id' => 'submitbutton',
                'class'=>'btn btn-default',
            ),
        ));
    }
}