<?php
namespace ManageComponent\Form;
use Zend\InputFilter\InputFilter;

class DepartmentFilter extends InputFilter {	
	public function __construct(){
		
		$this->add(array(
			'name' => 'dept_name',
			'required' => true,
			'filters' => array(
				 array('name' => 'StripTags'),
                 array('name' => 'StringTrim')
			),
			'validators' => array(array(
				'name' => 'StringLength',
				'options' => array(
					'encoding' => 'UTF-8',
					'min' => 2,
					'max' => 100,
					'messages' => array(
						\Zend\Validator\StringLength::TOO_SHORT => 'Department name must be at least 2 characters in length',
						\Zend\Validator\StringLength::TOO_LONG => 'Department name must be no longer than 100 characters in length',
					),
				),
			)),
		));

	}
}
