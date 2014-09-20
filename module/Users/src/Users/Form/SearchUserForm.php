<?php

namespace Users\Form;
use Zend\Form\Form;

class SearchUserForm extends Form {
	public function __construct($name = null) {		
		parent::__construct('SearchUserForm');		
		$this->setAttribute('method', 'get');
		$this->setAttribute('enctype','multipart/form-data');
		
		$this->add(array(
			'name' => 'search',
			'attributes' => array(
				'type' => 'text',				
				'class'	=>'form-control',
				'placeholder'=>"Name / Email",
			),
				'options' => array(
				'label' => 'Name / Email',
				)
		));
		$this->add(array(     
		    'type' => 'Select',       
		    'name' => 'status',
		    'attributes' =>  array(
		        'id' => 'usernames', 
		        "class"=>"form-control",               
		        'options' => array(
		        	'' => 'Select',
		            'active' => 'Active',
		            'inactive' => 'In Active',
		        ),
		    ),
		    'options' => array(
		        'label' => 'Select Status',
		    ),
		));

		$this->add(array(
			'name' => 'submit',
			'attributes' => array(
				'type' => 'submit',
				'Value' => 'Submit',
			),
		));
		
	}
}
