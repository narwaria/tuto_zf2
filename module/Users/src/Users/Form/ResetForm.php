<?php

namespace Users\Form;
use Zend\Form\Form;

class ResetForm extends Form {
	public function __construct($name = null) {
		
		parent::__construct('reset');
		
		$this->setAttribute('method', 'post');
		$this->setAttribute('enctype','multipart/form-data');
		


		$this->add(array(
			'name' => 'password',
			'attributes' => array(
				'type' => 'password',
				'required' => 'required',
				'class' => 'form-control',
				'placeholder'=>"Password *",
			),
			'options' => array(
				'label' => 'password',
			),			
		));
		
		$this->add(array(
			'name' => 'confirm_password',
			'attributes' => array(
				'type' => 'password',
				'required' => 'required',
				'class' => 'form-control',
				'placeholder'=>"Confirm Password *",
			),
			'options' => array(
				'label' => 'Confirm password',
			),
		));
		
		$this->add(array(
			'name' => 'submit',
			'attributes' => array(
				'type' => 'submit',
				'Value' => 'Login',
				'class'	=>	'btn btn-io mt-10 login-button',
			),
		));  
		
	}
}
