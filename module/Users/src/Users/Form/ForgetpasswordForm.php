<?php

namespace Users\Form;
use Zend\Form\Form;

class ForgetpasswordForm extends Form {
	public function __construct($name = null) {
		
		parent::__construct('ForgetPassword');
		
		$this->setAttribute('method', 'post');
		$this->setAttribute('enctype','multipart/form-data');
		


		$this->add(array(
			'name' => 'email',
			'attributes' => array(
				'type' => 'email',
				'required' => 'required',
				'class'	=>'form-control pl-20',
				'placeholder'=>"Email ID of your Account *",
			),
			'options' => array(
				'label' => 'Email',
			),
			'filters' => array(array(
				'name' => 'StringTrim'),
			),
			'validators' => array(array(
				'name' => 'EmailAddress',
				'options' => array(
					'messages' => array(\Zend\Validator\EmailAddress::INVALID_FORMAT => 'Email address format is invalid')
				)
			))
		));
		
		
		
		$this->add(array(
			'name' => 'submit',
			'attributes' => array(
				'type' => 'submit',
				'Value' => 'Send Reset password link',
				'class'	=>	'btn btn-io mt-10',
			),
		));   
	}
}
