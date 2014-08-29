<?php
// module/ManageComponent/src/ManageComponent/Model/Category.php:
namespace ManageComponent\Model;

use Zend\InputFilter\InputFilter;
use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class Category implements InputFilterAwareInterface
{
    public $cat_id;
    public $name;
    public $parent_id;
    public $departments_count;
    public $designations_count;
    protected $inputFilter;

    public function exchangeArray($data)
    {
        $this->cat_id               = (isset($data['cat_id'])) ? $data['cat_id'] : null;
        $this->name                 = (isset($data['name'])) ? $data['name'] : null;
        $this->parent_id            = (isset($data['parent_id'])) ? $data['parent_id'] : null;
        $this->departments_count    = (isset($data['departments_count'])) ? $data['departments_count'] : null;
        $this->designations_count   = (isset($data['designations_count'])) ? $data['designations_count'] : null;        
        
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