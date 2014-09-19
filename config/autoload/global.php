<?php
/**
<<<<<<< HEAD
* Global Configuration Override
*
* You can use this file for overridding configuration values from modules, etc.
* You would place values in here that are agnostic to the environment and not
* sensitive to security.
*
* @NOTE: In practice, this file will typically be INCLUDED in your source
* control, so do not include passwords or other sensitive information in this
* file.
*/

/*
return array(
// ...
);
*/

return array(
    'db' => array(
        'driver' => 'Pdo',
        'dsn' => 'mysql:dbname=interview_app;host=localhost',
        'driver_options' => array(
            PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\''
        ),
    ),
    'service_manager' => array(
        'factories' => array(
            'Zend\Db\Adapter\Adapter'  => 'Zend\Db\Adapter\AdapterServiceFactory',
        ),	
		/* Moved to Auth module to allow to be replaced by Doctrine or other.
		// added for Authentication and Authorization. Without this each time we have to create a new instance.
		// This code should be moved to a module to allow Doctrine to overwrite it
        'aliases' => array( // !!! aliases not alias
            'Zend\Authentication\AuthenticationService' => 'my_auth_service',
        ),
        'invokables' => array(
            'my_auth_service' => 'Zend\Authentication\AuthenticationService',
        ),
		*/
    ),
	
	//'static_salt' => 'aFGQ475SDsdfsaf2342', // was moved from module.config.php here to allow all modules to use it
        'interview_constants'=>array(
                    'default_login_attempts'	=>	3,
                    'default_pager_elements'	=>	10,
                    'default_date_format'	=>	'dd-mm-YYYY',
                    'default_max_options'	=>	6,
                    'default_admin_email'	=>	'aloknarwaria@gmail.com',//'info@stigasoft.com'
                ),
        'interview_menus'=>array(
                    '1'=>'Manage Users',
                    '2'=>'Manage Role',
                    '3'=>'Manage Department',
                    '4'=>'Manage Designation',
                    '5'=>'Manage Technology',
                    '6'=>'Manage Topic',
                    '7'=>'Manage Question',
                    '8'=>'Interview Set',
                    '9'=>'Scheduled Interview'
                ),
        'interview_user_permission'=>array(                    
                    '1'=>'Manage Component(View)',
                    '2'=>'Manage Component(Full)',                    
                    '3'=>'Manage Question Bank (View)',
                    '4'=>'Manage Question Bank(Full)',
                    '5'=>'Manage Interview (View)',
                    '6'=>'Manage Interview (Full)',
                ),
        'module_layouts' => array(
                    'Application' => 'layout/layout.phtml',
                    'Dashboard' => 'layout/dashboard.phtml',
                ),
    
);