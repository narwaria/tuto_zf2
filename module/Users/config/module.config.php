<?php
namespace Users;

return array(
    'controllers' => array(
        'invokables' => array(            
            'Users' => 'Users\Controller\UsersController',
        ),
    ),
    'router' => array(        
        'routes' => array(


            
            'users' => array(
                'type' => 'segment',               
                'options' => array(
                    'route' => '/[:controller][/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Users',
                        'action' => 'index',
                    ),
                ),
                
            ),
        ),        
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            'users' => __DIR__ . '/../view',
        ),
    ),
);
