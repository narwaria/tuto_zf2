<?php

namespace Users\Form;
use Zend\Form\Form;

class AddUserForm extends Form {
	public function __construct($name = null) {		
		parent::__construct('AddUser');		
		$this->setAttribute('method', 'post');
		//$this->setAttribute('enctype','multipart/form-data');
		
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
				'required' => 'required',
				'class'	=>'form-control pl-20',
				'placeholder'=>"Password *",
			),
			'options' => array(
				'label' => 'Password',
			),
		));

		$this->add(array(
			'name' => 'confirm_password',
			'attributes' => array(
				'type' => 'password',
				'required' => 'required',
				'class'	=>'form-control pl-20',
				'placeholder'=>"Confirm Password *",
			),
			'options' => array(
				'label' => 'Password',
			),
		));		
		$this->add(array(
			'name' => 'designation',
			'attributes' => array(
				'type' => 'text',
				'class'	=>'form-control pl-20',
				'placeholder'=>"Designation",
			),
			'options' => array(
				'label' => 'Organization Number',
			),			
		));
		$this->add(array(
			'name' => 'organisation',
			'attributes' => array(
				'type' => 'text',
				'class'	=>'form-control pl-20',
				'placeholder'=>"Organization",
			),
			'options' => array(
				'label' => 'Organization Number',
			),			
		));
		$this->add(array(
			'name' => 'phone',
			'attributes' => array(
				'type' => 'text',
				'class'	=>'form-control pl-20',
				'placeholder'=>"Phone No.",
			),
			'options' => array(
				'label' => 'Phone Number',
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
