<?php
namespace ManageComponent\Form;
use Zend\InputFilter\InputFilter;

class DesignationFilter extends InputFilter {	
	public function __construct(){
		
		$this->add(array(
			'name' => 'desig_name',
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
						\Zend\Validator\StringLength::TOO_SHORT => 'Designation name must be at least 2 characters in length',
						\Zend\Validator\StringLength::TOO_LONG => 'Designation name must be no longer than 100 characters in length',
					),
				),
			)),
		));
		
		$this->add(array(
			'name' => 'depts',
			'required' => true,
			'validators' => array(array(
				'name' => 'notEmpty',
				'options' => array(
					'messages' => array(
                        'isEmpty'=>'You must select atleast one designation',
					),
				),
			)),
		));

	}
}
