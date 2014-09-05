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

return array(
	'interview_constants'=>array(
		'default_login_attempts'		=>	3,
		'default_pager_elements'	=>	10,
		'default_date_format'		=>	'dd-mm-YYYY',
		'default_max_options'		=>	6,
		'default_admin_email'		=>	'aloknarwaria@gmail.com',//'info@stigasoft.com'
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
	'module_layouts' => array(
       'Application' => 'layout/layout.phtml',
       'Dashboard' => 'layout/dashboard.phtml',
   	),
    // ...
);
