<?php

namespace Users\Form;
use Zend\Form\Form;

class DeleteUserForm extends Form {
	public function __construct($name = null) {		
		parent::__construct('DeleteUserForm');		
		$this->setAttribute('method', 'post');
		$this->setAttribute('enctype','multipart/form-data');
		
		$this->add(array(
			'name' => 'user_id',
			'attributes' => array(
				'type' => 'hidden',				
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
