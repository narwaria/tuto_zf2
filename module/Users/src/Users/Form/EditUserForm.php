<?php

namespace Users\Form;
use Zend\Form\Form;

class EditUserForm extends Form {
	public function __construct($name = null) {		
		parent::__construct('EditUser');		
		$this->setAttribute('method', 'post');
		$this->setAttribute('enctype','multipart/form-data');
		
		$this->add(array(
			'name' => 'fname',
			'attributes' => array(
				'type' => 'text',
				'required' => 'required',
				'class'	=>'form-control pl-20',
				'placeholder'=>"First Name *",
			),
				'options' => array(
				'label' => 'First Name',
				)
		));

		$this->add(array(
			'name' => 'lname',
			'attributes' => array(
				'type' => 'text',
				'required' => 'required',
				'class'	=>'form-control pl-20',
				'placeholder'=>"Last Name *",
			),
			'options' => array(
				'label' => 'Last Name',
			),			
		));
		
		$this->add(array(
			'name' => 'email',
			'attributes' => array(
				'type' => 'email',
				'required' => 'required',
				'required' => 'required',
				'class'	=>'form-control pl-20',
				'placeholder'=>"Email Address *",
			),
			'options' => array(
				'label' => 'Email',
			),					
		));
		
		$this->add(array(
			'name' => 'password',
			'attributes' => array(
				'type' => 'password',				
				'class'	=>'form-control pl-20',
				'placeholder'=>"Password *",
				'autocomplete'=>"off",
			),
			'options' => array(
				'label' => 'Password',
			),
		));

		$this->add(array(
			'name' => 'confirm_password',
			'attributes' => array(
				'type' => 'password',				
				'class'	=>'form-control pl-20',
				'placeholder'=>"Confirm Password *",
				'autocomplete'=>"off",
			),
			'options' => array(
				'label' => 'Password',
			),
		));
		$this->add(array(     
		    'type' => 'Select',       
		    'name' => 'status',
		    'attributes' =>  array(
		        'id' => 'user-status', 
		        "class"=>"form-control",               
		        'options' => array(		        	
		            '1' => 'Active',
		            '0' => 'In Active',
		        ),
		    ),
		    'options' => array(
		        'label' => 'Select Status',
		    ),
		));
		$this->add(array(
             'type' => 'Zend\Form\Element\MultiCheckbox',
             'name' => 'permission',
             'options' => array(
                'label' => 'User Permission',
                'label_attributes' => array(
                    'class' => 'col-xs-12 col-md-6 checkbox',
                ),
                'disable_inarray_validator' => true,
                'value_options' => array(),
             )
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
