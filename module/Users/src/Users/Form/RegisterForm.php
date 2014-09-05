<?php

namespace Users\Form;
use Zend\Form\Form;

class RegisterForm extends Form {
	public function __construct($name = null) {
		
		parent::__construct('Register');
		
		$this->setAttribute('method', 'post');
		$this->setAttribute('enctype','multipart/form-data');
		
		$this->add(array(
			'name' => 'name',
			'attributes' => array(
				'type' => 'text',
				'required' => 'required',
				'class'	=>'form-control pl-20',
				'placeholder'=>"First Name *",
			),
			'options' => array(
				'label' => 'First Name',
			),
			'validators' => array(array(
				'name' => 'StringLength',
				'options' => array(
					'encoding' => 'UTF-8',
					'min'      => 2,
					'max'      => 50,
				)
			))
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
			'validators' => array(array(
				'name' => 'StringLength',
				'options' => array(
					'encoding' => 'UTF-8',
					'min'      => 2,
					'max'      => 50,
				)
			))
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
				'class'	=>'form-control pl-20',
				'placeholder'=>"Password *",
			),
			'options' => array(
				'label' => 'Password',
			),
		));

		$this->add(array(
			'name' => 'phone_number',
			'attributes' => array(
				'type' => 'text',
				'class'	=>'form-control pl-20',
				'placeholder'=>"Phone No.",
			),
			'options' => array(
				'label' => 'Phone Number',
			),
			/*'validators' => array(array(
				'name' => 'StringLength',
				'options' => array(
					'encoding' => 'UTF-8',
					'min'      => 5,
					'max'      => 50,
				)
			))*/
		));
		$this->add(array(
			'name' => 'org_name',
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
			'name' => 'designation_name',
			'attributes' => array(
				'type' => 'text',
				'class'	=>'form-control pl-20',
				'placeholder'=>"Designation",
			),
			'options' => array(
				'label' => 'Organization Number',
			),			
		));
		
		/*$this->add(array(
			'name' => 'confirm_password',
			'attributes' => array(
				'type' => 'password',
				'required' => 'required',
			),
			'options' => array(
				'label' => 'Confirm Password',
			),
		)); 
		
		$this->add(array(
			'name' => 'address',
			'attributes' => array(
				'type' => 'text',
				'required' => 'required',
			),
			'options' => array(
				'label' => 'Address',
			),
		));
		
		$this->add(array(
			'name' => 'phone',
			'attributes' => array(
				'type' => 'text',
				'required' => 'required',
			),
			'options' => array(
				'label' => 'Phone no.',
			),
		));
		*/
		$this->add(array(
			'name' => 'submit',
			'attributes' => array(
				'type' => 'submit',
				'Value' => 'Submit',
			),
		));
		
	}
}
