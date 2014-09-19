<?php

namespace ManageComponent\Form;
use Zend\Form\Form;

class SkillForm extends Form {
    
    public function __construct($name = null)
    {
        // we want to ignore the name passed
        parent::__construct('skill');
        
        $this->setAttribute('method', 'post');
        
        $this->add(array(
            'name' => 'skill_id',
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
            'name' => 'skill_name',
            'attributes' => array(
                'required'=> false,
                'type'  => 'text',
                'class'=>'form-control',
                'placeholder'=>"Skill Name"
            ),
            'options' => array(
                'label' => 'Skill Name *',
                'label_attributes' => array(
                    'class' => 'col-xs-12 col-md-3 control-label',
                )
            ),
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
