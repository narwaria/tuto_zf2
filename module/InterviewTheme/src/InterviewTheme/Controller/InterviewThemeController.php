<?php

namespace InterviewTheme\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class InterviewThemeController extends AbstractActionController
{

    public function indexAction()
    {
        return new ViewModel();
    }


}

