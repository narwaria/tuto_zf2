<?php
// module/Album/src/Album/Form/AlbumForm.php:
namespace ManageComponent\Form;

use Zend\Form\Form;

class DesignationFormSearch extends Form
{
    public function __construct($name = null)
    {
        // we want to ignore the name passed
        parent::__construct('designation');
        
        $this->setAttribute('method', 'get');        
        
        $this->add(array(
            'name' => 'desig_name',
            'attributes' => array(
                'type'  => 'text',
                'placeholder' => 'Designation Name',
                'class' => 'form-control',
            ),
            'options' => array(
                'label' => 'Designation Name',
            ),
        ));
        
        $this->add(array(
			'type' => 'Zend\Form\Element\Select',
			'name' => 'depts',
			'attributes' => array(
				'class' => 'form-control',
			),
			'options' => array(
                'label' => 'Department',
                'value_options' => array(),
            )
        ));
        
        $this->add(array(
            'name' => 'submit',
            'attributes'=> array(
                'type'	=> 'submit',
                'value'	=> 'Search Designation',
                'id'	=> 'submitbutton',
                'class'	=> 'btn btn-io',
            ),
        ));
    }
}
