<?php
namespace Users\Controller\Plugin;
 
use Zend\Mvc\Controller\Plugin\AbstractPlugin;

//smtp mail 
use Zend\Mail\Message;
use Zend\Mail\Transport\Smtp as SmtpTransport;
use Zend\Mime\Message as MimeMessage;
use Zend\Mime\Part as MimePart;
use Zend\Mail\Transport\SmtpOptions;

class UsersCommonFuncions extends AbstractPlugin{
    public function CheckDatetimeRange($datetime=null){
    	$hours	= 	number_format( ((time()-$datetime)/3600) ,2);
    	return ($hours>24)?false:true;
   	}		
}