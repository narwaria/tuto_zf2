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
        $this->setAttribute('method', 'post');        
        $this->add(array(
            'name' => 'topic_name',
            'attributes' => array(
                'type'  => 'text',
            ),
            'options' => array(
                'label' => 'Topic Name',
            ),
        ));
        $this->add(array(
            'name' => 'topic_description',
            'attributes' => array(
                'type'  => 'text',
            ),
            'options' => array(
                'label' => 'Topic Description',
            ),
        ));  
        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type'  => 'submit',
                'value' => 'Search Topic',
                'id' => 'submitbutton',
            ),
        ));
    }
}