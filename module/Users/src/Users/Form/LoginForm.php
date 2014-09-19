<?php

namespace Users\Form;
use Zend\Form\Form;

class LoginForm extends Form {
	public function __construct($name = null) {
		
		parent::__construct('Login');
		
		$this->setAttribute('method', 'post');
		$this->setAttribute('enctype','multipart/form-data');
		


		$this->add(array(
			'name' => 'email',
			'attributes' => array(
				'type' => 'email',
				'required' => 'required',
				'class' => 'form-control',
				'placeholder' => 'Email Address',
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
			'name' => 'password',
			'attributes' => array(
				'type' => 'password',
				'required' => 'required',
				'class' => 'form-control',
				'placeholder' => 'Password',
			),
			'options' => array(
				'label' => 'Password',
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
        
        $this->add(array(
			'name' => 'rem_me',
            'type' => 'checkbox',
			'attributes' => array(
				'Value' => 'yes',
			),
            'options' => array(
				'label' => 'Remember Me',
			),
		));
		
	}
}
