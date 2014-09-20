<?php
namespace Users\Form\View\Helper;
use Zend\Form\ElementInterface;
use Zend\Form\View\Helper\FormElement;
class ErrorFormElement extends FormElement{
    
    public function render(ElementInterface $element){
        $errors = $element->getMessages();
        if (! empty($errors)) {
            $classes = $element->getAttribute('class');
            if (null === $classes) $classes = array();
            if (! is_array($classes)) $classes = explode(' ', $classes);
            $classes = array_unique(array_merge($classes, array('error')));
            $element->setAttribute('class', implode(' ', $classes));
        }
        return parent::render($element);
    }
    
}