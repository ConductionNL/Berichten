<?php

namespace App\Subscriber;

use ApiPlatform\Core\EventListener\EventPriorities;
use Doctrine\Common\Annotations\Reader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Serializer\SerializerInterface;

use App\Entity\Message;


use App\Service\MailService;
use App\Service\MessageService;

class MessageSubscriber implements EventSubscriberInterface
{
    private $params;
    private $em;
    private $mailService;
    private $messageService;

    public function __construct(ParameterBagInterface $params, EntityManagerInterface $em, MailService $mailService, MessageService $messageService)
    {
        $this->params = $params;
        $this->em = $em;
        $this->mailService = $mailService;
        $this->messageService = $messageService;
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => ['message', EventPriorities::PRE_SERIALIZE],
        ];
    }

    public function message(GetResponseForControllerResultEvent $event)
    {
    	$result = $event->getControllerResult();
    	$method = $event->getRequest()->getMethod();
    	
    	if (!$result instanceof Message || $result->getStatus() != 'queued' || $result->getSend() != null){
    		return;
    	}
    	
    	switch ($result->getService()->getType()) {
    		case 'mailer':
    			$result = $this->mailService->sendEmail($result);
    			break;
    		case 'messagebird':
    			$result = $this->messageService->sendMessage($result);
    			break; 
    	}
    	
    	$em->persist($result);
    	$em->flush($result);
        

        return $result;
    }
}
