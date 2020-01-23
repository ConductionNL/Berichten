<?php
// src/Service/HuwelijkService.php
namespace App\Service;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Cache\Adapter\AdapterInterface as CacheInterface;

use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\Transport;

Use App\Entity\Message;
Use App\Service\CommonGroundService;

class MailService
{
	private $params;
	private $cache;
	private $commonGroundService;
	
	public function __construct(ParameterBagInterface $params, CacheInterface $cache,  CommonGroundService $commonGroundService)
	{
		$this->params = $params;
		$this->cash = $cache;
		$this->commonGroundService = $commonGroundService;
		
	}

	public function sendEmail(Message $message)
	{
		$transport = Transport::fromDsn($message->getService()->getAuthorization());
		$mailer = new Mailer($transport);	
		
		$sender = $this->commonGroundService->getResource($message->getSender());
    	$reciever = $this->commonGroundService->getResource($message->getReciever());
    	$content = $this->commonGroundService->getResource($message->getContent());
    	//$html = $this->commonGroundService->getResource($message->getContent().'/render');
    	$html = $content['content'];
    	$text = strip_tags(preg_replace('#<br\s*/?>#i', "\n", $html), '\n');
    	
        $email = (new Email())
        	->from($sender['emails'][0]['email'])
        	->to($reciever['emails'][0]['email'])
            //->cc('cc@example.com')
            //->bcc('bcc@example.com')
            //->replyTo('fabien@example.com')
            //->priority(Email::PRIORITY_HIGH)
    		->subject($content['name'])
    		->html($html)
        	->text($text);

        /** @var Symfony\Component\Mailer\SentMessage $sentEmail */
        $sentEmail = $mailer->send($email);
        // $messageId = $sentEmail->getMessageId();

        // ...
        
        var_dump($sentEmail);
        die;        
        
        $message->setSend(New \Datetime);
        $message->setStatus('send');
        $message->setServiceId($sentEmail->getMessageId());
        //
        return $message;
    }
	
}
