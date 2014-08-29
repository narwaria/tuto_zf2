<?php
// module/Album/src/Album/Model/Album.php:
namespace TopicManagement\Model;

use Zend\InputFilter\InputFilter;
use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class Topic implements InputFilterAwareInterface
{
    public $topic_id;
    public $topic_name;
    public $topic_description;
    protected $inputFilter;

    public function exchangeArray($data)
    {
        $this->topic_id     = (isset($data['topic_id']))     ? $data['topic_id']     : null;
        $this->topic_name   = (isset($data['topic_name'])) ? $data['topic_name'] : null;
        $this->topic_description = (isset($data['topic_description'])) ? $data['topic_description'] : null;
        
    }

     // Add the following method:
    public function getArrayCopy()
    {
        return get_object_vars($this);
    }

    public function setInputFilter(InputFilterInterface $inputFilter)
    {
        throw new \Exception("Not used");
    }

    public function getInputFilter()
    {
        if (!$this->inputFilter) {
            $inputFilter = new InputFilter();
            $factory     = new InputFactory();
            $inputFilter->add($factory->createInput(array(
                'name'     => 'topic_name',
                'required' => true,
                'filters'  => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name'    => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min'      => 1,
                            'max'      => 100,
                        ),
                    ),
                ),
            )));

            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }
}