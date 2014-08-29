<?php
namespace Album;

return array(
    'controllers' => array(
        'invokables' => array(            
            'Album' => 'Album\Controller\AlbumController',
        ),
    ),
    'router' => array(        
        'routes' => array(
            'album' => array(
                'type' => 'Literal',               
                'options' => array(
                    'route' => '/album',
                    'defaults' => array(
                        'controller' => 'Album',
                        'action'     => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'add' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/add',
                            'defaults' => array(
                                'controller' => 'Album',
                                'action'     => 'add',
                            ),
                        ),
                    ),                                       
                ),
            ),
        ),        
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            
            'album' => __DIR__ . '/../view',
        ),
        'template_map' => array(
            'paginator-slide' => __DIR__ . '/../view/layout/slidePaginator.phtml',
        ),
    ),

);
