<?php

namespace Users\Form;
use Zend\InputFilter\InputFilter;

class ResetFilter extends InputFilter {
	
	public function __construct(){
		$this->add(array(
			'name' => 'password',
			'required' => true,
			'filters' => array(
				 array('name' => 'StripTags'),
                 array('name' => 'StringTrim')
			),
			'validators' => array(array(
				'name' => 'StringLength',
				'options' => array(
					'encoding' => 'UTF-8',
					'min' => 8,
					'max' => 20,
					'messages' => array(
                            \Zend\Validator\StringLength::TOO_SHORT => 'Password field must be at least 8 characters in length',
                            \Zend\Validator\StringLength::TOO_LONG => 'Password field must be no longer than 20 characters in length',
                        ),
				),
			)),
		));
		$this->add(array(
		    'name' => 'confirm_password', // add second password field
		    /* ... other params ... */
		    'validators' => array(
		        array(
		            'name' => 'Identical',
		            'options' => array(
		                'token' => 'password', // name of first password field
		            ),
		        ),
		    ),
		));
		
	}
}
