<?php
namespace Users\Controller\Plugin;
 
use Zend\Mvc\Controller\Plugin\AbstractPlugin;

//smtp mail 
use Zend\Mail\Message;
use Zend\Mail\Transport\Smtp as SmtpTransport;
use Zend\Mime\Message as MimeMessage;
use Zend\Mime\Part as MimePart;
use Zend\Mail\Transport\SmtpOptions;

class AuthService extends AbstractPlugin{
    public function AuthService($to,$subject,$body,$tokenKeyValues=array()){
    	
   	}	
}