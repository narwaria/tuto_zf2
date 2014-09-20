<?php
// module/Album/src/Album/Form/AlbumForm.php:
namespace ManageComponent\Form;

use Zend\Form\Form;

class TopicFormSearch extends Form
{
    public function __construct($name = null)
    {
        // we want to ignore the name passed
        parent::__construct('topic');
        $this->setAttribute('method', 'get');        
        $this->add(array(
            'name' => 'topic_name',
            'attributes' => array(
                'type'  => 'text',
                'placeholder' => 'Topic Name',
                'class' => 'form-control',
            ),
            'options' => array(
                'label' => 'Topic Name',
            ),
        ));
        
        $this->add(array(
             'type' => 'Zend\Form\Element\Select',
             'name' => 'skills',
             'attributes' => array(
                 'class' => 'form-control',
             ),
        ));
        
        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type'  => 'submit',
                'value' => 'Search Topic',
                'id' => 'submitbutton',
                'class' => 'btn btn-io',
            ),
        ));
    }
}