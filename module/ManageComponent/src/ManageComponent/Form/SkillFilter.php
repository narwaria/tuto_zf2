<?php
namespace ManageComponent\Form;
use Zend\InputFilter\InputFilter;

class SkillFilter extends InputFilter {	
	public function __construct(){
		
		$this->add(array(
			'name' => 'skill_name',
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
						\Zend\Validator\StringLength::TOO_SHORT => 'Skill name must be at least 2 characters in length',
						\Zend\Validator\StringLength::TOO_LONG => 'Skill name must be no longer than 100 characters in length',
					),
				),
			)),
		));

	}
}
