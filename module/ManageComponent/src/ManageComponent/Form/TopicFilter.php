<?php
namespace ManageComponent\Form;
use Zend\InputFilter\InputFilter;

class TopicFilter extends InputFilter {	
	public function __construct(){
		
		$this->add(array(
			'name' => 'topic_name',
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
						\Zend\Validator\StringLength::TOO_SHORT => 'Topic name must be at least 2 characters in length',
						\Zend\Validator\StringLength::TOO_LONG => 'Topic name must be no longer than 100 characters in length',
					),
				),
			)),
		));
		
		$this->add(array(
			'name' => 'skill',
			'required' => true,
			'validators' => array(array(
				'name' => 'notEmpty',
				'options' => array(
					'messages' => array(
                        'isEmpty'=>'You must select atleast one skill',
					),
				),
			)),
		));

	}
}
