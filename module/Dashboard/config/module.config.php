<?php
namespace Dashboard;

return array(
    'controllers' => array(
        'invokables' => array(            
            'Dashboard' => 'Dashboard\Controller\DashboardController',
        ),
    ),
    'router' => array(        
        'routes' => array(
            'dashboard' => array(
                'type' => 'Literal',               
                'options' => array(
                    'route' => '/admin/dashboard',
                    'defaults' => array(
                        'controller' => 'Dashboard',
                        'action'     => 'index',
                    ),
                ),
                'may_terminate' => true,               
            ),
        ),        
    ),
    'view_manager' => array(
        'template_path_stack' => array(            
            'dashboard' => __DIR__ . '/../view',
        ),       
    ),
);