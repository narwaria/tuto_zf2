<?php
namespace Users;

return array(
    'controllers' => array(
        'invokables' => array(            
            'Users\Controller\Users' => 'Users\Controller\UsersController',
            'Users\Controller\UserManager'      =>  'Users\Controller\UserManagerController',
        ),
    ),
    'router' => array(        
        'routes' => array(
            'users' => array(
                'type' => 'segment',               
                'options' => array(
                    'route' => '/user[/:action][/:param]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'param' => '[a-zA-Z][a-zA-Z0-9_=-]*',
                    ),
                    'defaults' => array(
                        'controller' => 'Users\Controller\Users',
                        'action' => 'index',
                    ),
                ),
                
            ),

            // Topic Routing...
            'manageuser' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/manage/user[/:action][/:topic_id][/page/:page][/order_by/:order_by][/:order][/search_by/:search_by]',
                    'constraints' => array(
                        'action' => '(?!\bpage\b)(?!\border_by\b)[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                        'page'   => '[0-9]+',
                        'order_by'=> '[a-zA-Z][a-zA-Z0-9_-]*',
                        'order'  => 'ASC|DESC',
                    ),
                    'defaults' => array(
                        'controller' => 'Users\Controller\UserManager',
                        'action'     => 'index',
                    ),
                ),
            ),
            //End Topic


        ),        
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            'users' => __DIR__ . '/../view',
        ),
    ),
);
