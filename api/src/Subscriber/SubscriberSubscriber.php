<?php

namespace App\Subscriber;

use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\Subscriber;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class SubscriberSubscriber implements EventSubscriberInterface
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => ['subscriber', EventPriorities::PRE_VALIDATE],
        ];
    }

    public function subscriber(ViewEvent $event)
    {
        $subscriber = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();
        $route = $event->getRequest()->attributes->get('_route');

        if (($route != 'api_subscribers_post_collection') || (Request::METHOD_POST !== $method)) {
            return;
        }
        if ($subscriber instanceof Subscriber) {
            if (!$subscriber->getResource() && !$subscriber->getEmail()) {
                throw new Exception('email & resource: Deze waardes mogen niet beide null zijn.');
            }
            if ($subscriber->getEmail() and $route == 'api_subscribers_post_collection' and $newSubscriber = $this->em->getRepository(Subscriber::class)->findOneBy(['email' => $subscriber->getEmail()])) {
                throw new Exception('There already is a subscriber with this email.');
            }
            if ($subscriber->getResource() and $route == 'api_subscribers_post_collection' and $newSubscriber = $this->em->getRepository(Subscriber::class)->findOneBy(['resource' => $subscriber->getResource()])) {
                throw new Exception('There already is a subscriber with this resource.');
            }
        }

        $this->em->persist($subscriber);
        $this->em->flush();

        return $subscriber;
    }
}
