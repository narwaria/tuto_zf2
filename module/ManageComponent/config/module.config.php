<?php
namespace TopicManagement;
return array(
    'controllers' => array(
        'invokables' => array(
            'ManageComponent\Controller\Topic' 			=>	'ManageComponent\Controller\TopicController',
            'ManageComponent\Controller\Department' 	=>	'ManageComponent\Controller\DepartmentController',
            'ManageComponent\Controller\Designation' 	=>	'ManageComponent\Controller\DesignationController',
            'ManageComponent\Controller\Technology' 	=>	'ManageComponent\Controller\TechnologyController',
        ),
    ),
    // The following section is new and should be added to your file
    'router' => array(
        'routes' => array(
        	// Topic Routing...
            'topic' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/admin/topic[/:action][/:topic_id][/page/:page][/order_by/:order_by][/:order][/search_by/:search_by]',
                    'constraints' => array(
                        'action' => '(?!\bpage\b)(?!\border_by\b)[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                        'page' => '[0-9]+',
                        'order_by' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'order' => 'ASC|DESC',
                    ),
                    'defaults' => array(
                        'controller' => 'ManageComponent\Controller\Topic',
                        'action'     => 'index',
                    ),
                ),
            ),
            //End Topic

            // Department Routing...
            'department' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/admin/department[/:action][/:topic_id][/page/:page][/order_by/:order_by][/:order][/search_by/:search_by]',
                    'constraints' => array(
                        'action' => '(?!\bpage\b)(?!\border_by\b)[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                        'page' => '[0-9]+',
                        'order_by' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'order' => 'ASC|DESC',
                    ),
                    'defaults' => array(
                        'controller' => 'ManageComponent\Controller\Department',
                        'action'     => 'index',
                    ),
                ),
            ),
            // End Department


            // Designation Routing...
            'designation' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/admin/designation[/:action][/:topic_id][/page/:page][/order_by/:order_by][/:order][/search_by/:search_by]',
                    'constraints' => array(
                        'action' => '(?!\bpage\b)(?!\border_by\b)[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                        'page' => '[0-9]+',
                        'order_by' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'order' => 'ASC|DESC',
                    ),
                    'defaults' => array(
                        'controller' => 'ManageComponent\Controller\Designation',
                        'action'     => 'index',
                    ),
                ),
            ),
            // End Designation


            // Technology Routing...
            'technology' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/admin/technology[/:action][/:topic_id][/page/:page][/order_by/:order_by][/:order][/search_by/:search_by]',
                    'constraints' => array(
                        'action' => '(?!\bpage\b)(?!\border_by\b)[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                        'page' => '[0-9]+',
                        'order_by' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'order' => 'ASC|DESC',
                    ),
                    'defaults' => array(
                        'controller' => 'ManageComponent\Controller\Technology',
                        'action'     => 'index',
                    ),
                ),
            ),
            // End Technology

        ),
    ),    
    
    'view_manager' => array(
        'template_path_stack' => array(
            'topic' => __DIR__ . '/../view',
        ),
        
    ),
     
);