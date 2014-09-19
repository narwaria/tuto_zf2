<?php
namespace ManageComponent\Form;

use Zend\Form\Form;

class DepartmentFormSearch extends Form
{
    public function __construct($name = null)
    {
        // we want to ignore the name passed
        parent::__construct('Department');
        
        $this->setAttribute('method', 'get');
        
        $this->add(array(
            'name' => 'dept_name',
            'attributes' => array(
                'type'  => 'text',
                'placeholder' => 'Department Name',
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
                'value' => 'Search Department',
                'id'	=> 'submitbutton',
                'class' => 'btn btn-io',
            ),
        ));
    }
}
