<?php

namespace Users\Form;
use Zend\Form\Form;
use Zend\Form\Element;
use Zend\Captcha\Image;
use Zend\Captcha\AdapterInterface;
class RegisterForm extends Form {
	public function __construct($name = null) {		
		parent::__construct('Register');		
		$this->setAttribute('method', 'post');
		$this->setAttribute('enctype','multipart/form-data');
                
                 $this->captcha = new Image(array(
                        'expiration' => '300',
                        'wordlen' => '7',
                        'font' => 'data/fonts/arial.ttf',
                        'fontSize' => '45',
                        'wordLen' => 5,
                        'height' => '100',
                        'width' => '250',
                        'imgDir' => 'public/captcha',
                        'imgUrl' => '/captcha'
                    ));
		
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
                        'name' => 'captcha',
                        'options' => array(
                            'label' => 'Verification',
                            'captcha' => $this->captcha,
                        ),
                        'attributes' => array(
				'class'	=>'form-control pl-20',
				'placeholder'=>"Type characters from the image *",
			),
                        'type'  => 'Zend\Form\Element\Captcha',
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
