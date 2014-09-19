<?php
namespace ManageComponent\Form;

use Zend\Form\Form;

class SkillFormSearch extends Form
{
    public function __construct($name = null)
    {
        // we want to ignore the name passed
        parent::__construct('skill');
        $this->setAttribute('method', 'get');        
        $this->add(array(
            'name' => 'skill_name',
            'attributes' => array(
                'type'  => 'text',
                'placeholder' => 'Skill Name',
                'class' => 'form-control',
            ),
            'options' => array(
                'label' => 'Skill Name',
            ),
        ));
        
        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type'  => 'submit',
                'value' => 'Search Skill',
                'id' => 'submitbutton',
                'class' => 'btn btn-io',
            ),
        ));
    }
}
