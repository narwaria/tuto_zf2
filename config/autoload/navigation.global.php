<?php
/**
 * Global Configuration Override
 *
 * You can use this file for overriding configuration values from modules, etc.
 * You would place values in here that are agnostic to the environment and not
 * sensitive to security.
 *
 * @NOTE: In practice, this file will typically be INCLUDED in your source
 * control, so do not include passwords or other sensitive information in this
 * file.
 */
// from http://framework.zend.com/manual/2.1/en/modules/zend.navigation.quick-start.html
// the array was empty before that
return array( // ToDO make it dynamic - comes from the DB
     'navigation' => array(
         'default' => array(
           /* array(
                'label' => 'Home',
                'route' => 'application',
                'action'     => 'index',
		'controller' => 'index',
                'resource'=> 'Application\Controller\Index',
                'privilege'  => 'index'
             ), */
            array(
                 'label' => 'Dashboard', // 'Page #1',
                 'route' => 'dashboard', // 'page-1',
                 'action'     => 'index',
                 'controller' => 'index',
                 'resource'=> 'Dashboard\Controller\Dashboard',
                 'privilege'    => 'index',               
             ),
            array(
                 'label' => 'Manage Role', // 'Page #1',
                 'route' => 'dashboard', // 'page-1',
                 'action'     => 'index',
                 'controller' => 'index',
                 'resource'=> 'Dashboard\Controller\Dashboard',
                 'privilege'    => 'index',               
             ),
             array(
                 'label' => 'Manage Department', // 'Page #1',
                 'route' => 'dashboard', // 'page-1',
                 'action'     => 'index',
                 'controller' => 'index',
                 'resource'=> 'Dashboard\Controller\Dashboard',
                 'privilege'    => 'index',               
             ),
             array(
                 'label' => 'Manage Designation', // 'Page #1',
                 'route' => 'dashboard', // 'page-1',
                 'action'     => 'index',
                 'controller' => 'index',
                 'resource'=> 'Dashboard\Controller\Dashboard',
                 'privilege'    => 'index',               
             ),
             array(
                 'label' => 'Manage Technology', // 'Page #1',
                 'route' => 'dashboard', // 'page-1',
                 'action'     => 'index',
                 'controller' => 'index',
                 'resource'=> 'Dashboard\Controller\Dashboard',
                 'privilege'    => 'index',               
             ),
             array(
                 'label' => 'Manage Topic', // 'Page #1',
                 'route' => 'topic', // 'page-1',
                 'action'     => 'index',
                 'controller' => 'Topic',
                 'resource'=> 'ManageComponent\Controller\TopicController',
                 'privilege'    => 'index',               
             ),
             array(
                 'label' => 'Manage Question', // 'Page #1',
                 'route' => 'dashboard', // 'page-1',
                 'action'     => 'index',
                 'controller' => 'index',
                 'resource'=> 'Dashboard\Controller\Dashboard',
                 'privilege'    => 'index',               
             ),
             array(
                 'label' => 'Interview Set', // 'Page #1',
                 'route' => 'dashboard', // 'page-1',
                 'action'     => 'index',
                 'controller' => 'index',
                 'resource'=> 'Dashboard\Controller\Dashboard',
                 'privilege'    => 'index',               
             ),
             array(
                 'label' => 'Scheduled Interview', // 'Page #1',
                 'route' => 'dashboard', // 'page-1',
                 'action'     => 'index',
                 'controller' => 'index',
                 'resource'=> 'Dashboard\Controller\Dashboard',
                 'privilege'    => 'index',               
             ),
         ),  
     ),
     'service_manager' => array(
         'factories' => array(
             'navigation' => 'Zend\Navigation\Service\DefaultNavigationFactory',			 
         ),
     ),
);

/*
action	String	NULL	Action name to use when generating href to the page.
controller	String	NULL	Controller name to use when generating href to the page.
params	Array	array()	User params to use when generating href to the page.
route	String	NULL	Route name to use when generating href to the page.
routeMatch	Zend\Mvc\Router\RouteMatch	NULL	RouteInterface matches used for routing parameters and testing validity.
router	Zend\Mvc\Router\RouteStackInterface	NULL	Router for assembling URLs
*/