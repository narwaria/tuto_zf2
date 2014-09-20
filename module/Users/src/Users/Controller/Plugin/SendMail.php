<?php
namespace Users\Controller\Plugin;
 
use Zend\Mvc\Controller\Plugin\AbstractPlugin;

//smtp mail 
use Zend\Mail\Message;
use Zend\Mail\Transport\Smtp as SmtpTransport;
use Zend\Mime\Message as MimeMessage;
use Zend\Mime\Part as MimePart;
use Zend\Mail\Transport\SmtpOptions;

class SendMail extends AbstractPlugin{
    public function SendMailSmtp($to,$subject,$body,$tokenKeyValues=array()){
    	$body 		= $this->tokenReplace($tokenKeyValues,$body);
    	$subject 	= $this->tokenReplace($tokenKeyValues,$subject);    	
    	$message = new Message();
		//$message->addTo("aloknarwaria@gmail.com")
		$message->addTo($to)		
				->addFrom('alok1606@gmail.com')
				->setSubject($subject);
		// Setup SMTP transport using LOGIN authentication
		$transport 	= new SmtpTransport();
		$options 	= new SmtpOptions(array(
							'host' => 'smtp.gmail.com',
							'connection_class' => 'login',
							'connection_config' => array('ssl' => 'tls',
														'username' => 'alok1606@gmail.com',
														'password' => 'narwaria'),
							'port' => 587,
					));		 
		$html = new MimePart($body);
		$html->type = "text/html";		 
		$body = new MimeMessage();
		$body->addPart($html);		 
		$message->setBody($body);		 
		$transport->setOptions($options);
		$transport->send($message);
   	}
	private function tokenReplace(array $tokenKeyValues, $msg) {
		   return str_replace(array_keys($tokenKeyValues), array_values($tokenKeyValues), $msg);   
	}	
}