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
            'name' => 'page',
            'attributes' => array(
                'type'  => 'hidden',
            ),
        ));
        
        $this->add(array(
            'name' => 'topic_name',
            'attributes' => array(
                'required'=> false,
                'type'  => 'text',
                'class'=>'form-control',
                'placeholder'=>"Topic Name"
            ),
            'options' => array(
                'label' => 'Topic Name *',
                'label_attributes' => array(
                    'class' => 'col-xs-12 col-md-3 control-label',
                )
            ),
        ));
        
        $this->add(array(
            'type' => 'Zend\Form\Element\MultiCheckbox',
            'name' => 'skill',
            'options' => array(
                'label' => 'Skill',
                'label_attributes' => array(
                    //'class' => 'col-xs-12 col-md-3 control-label',
                ),
                'value_options' => array(),
            )
        ));
        
        $this->add(array(
            'name' => 'client_id',
            'attributes' => array(
                'type'  => 'hidden',
            ),
        ));
        
        $this->add(array(
            'name' => 'created_by_user_id',
            'attributes' => array(
                'type'  => 'hidden',
            ),
        ));
        
        $this->add(array(
            'name' => 'created_date',
            'attributes' => array(
                'type'  => 'hidden',
            ),
        ));
        
        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type'  => 'submit',
                'value' => 'Save',
                'id' => 'submitbutton',
                'class'=>'btn btn-io',
            ),
        ));
        
        $this->add(array(
            'name' => 'reset',
            'attributes' => array(
                'type'  => 'reset',
                'value' => 'Reset',
                'id' => 'resetbutton',
                'class'=>'btn btn-io',
            ),
        ));
        
        $this->add(array(
            'name' => 'cancel',
            'attributes' => array(
                'type'  => 'button',
                'value' => 'Cancel',
                'id' => 'resetbutton',
                'class'=>'btn btn-io',
                'onclick' => 'javascript: window.history.back();',
            ),
        ));
        
    }
}
