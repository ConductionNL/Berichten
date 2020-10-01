<?php

// src/Service/HuwelijkService.php

namespace App\Service;

use App\Entity\Attachment;
use App\Entity\Message;
use Conduction\CommonGroundBundle\Service\CommonGroundService;
use GuzzleHttp\Client;
use Symfony\Component\Cache\Adapter\AdapterInterface as CacheInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mime\Email;

class MailService
{
    private $params;
    private $cache;
    private $commonGroundService;
    private $client;

    public function __construct(ParameterBagInterface $params, CacheInterface $cache, CommonGroundService $commonGroundService)
    {
        $this->params = $params;
        $this->cash = $cache;
        $this->commonGroundService = $commonGroundService;

        $this->client = new Client();
    }

    public function sendEmail(Message $message)
    {
        if ($message->getStatus() == 'queued') {
            $transport = Transport::fromDsn($message->getService()->getAuthorization());
            $mailer = new Mailer($transport);

            $variables = ['variables' => $message->getData()];

            $content = $this->commonGroundService->createResource($variables, $message->getContent().'/render');

            $html = $content['content'];
            $text = strip_tags(preg_replace('#<br\s*/?>#i', "\n", $html), '\n');

            if (filter_var($message->getSender(), FILTER_VALIDATE_URL)) {
                $sender = $this->commonGroundService->getResource($message->getSender());
                $sender = $sender['emails'][0]['email'];
            } else {
                $sender = $message->getSender();
            }

            if (filter_var($message->getReciever(), FILTER_VALIDATE_URL)) {
                $reciever = $this->commonGroundService->getResource($message->getReciever());
                $reciever = $reciever['emails'][0]['email'];
            } else {
                // force rebuilds
                $reciever = $message->getSender();
            }

            $email = (new Email())
                ->from($sender)
                ->to($reciever)
                //->cc('cc@example.com')
                //->bcc('bcc@example.com')
                //->replyTo('fabien@example.com')
                //->priority(Email::PRIORITY_HIGH)
                ->subject($content['name'])
                ->html($html)
                ->text($text);

            $filenames = [];
            foreach ($message->getAttachments() as $attachment) {
                $filename = $this->getAttachment($attachment);
                $email->attachFromPath($filename);
                $filenames[] = $filename;
            }

            /** @var Symfony\Component\Mailer\SentMessage $sentEmail */
            $mailer->send($email);

            $message->setSend(new \Datetime());
            $message->setStatus('send');

            foreach ($filenames as $filename) {
                unlink($filename);
            }
        }

        return $message;
    }

    public function getAttachment(Attachment $attachment)
    {
        $this->commonGroundService->setHeader('Accept', $attachment->getMime());
//        return $this->commonGroundService->createResource($attachment->getResources(), $attachment->getUri()."/render");
        $stamp = microtime();
        $filename = dirname(__FILE__, 3)."/var/{$attachment->getName()}";
        $file = fopen($filename, 'w+');

        $headers = $this->headers = [
            'Accept'         => $attachment->getMime(),
            'Content-Type'   => 'application/json',
            'Authorization'  => $this->params->get('app_commonground_key'),
            // NLX
            'X-NLX-Request-Application-Id' => $this->params->get('app_commonground_id'), // the id of the application performing the request
            // NL Api Strategie
            'Accept-Crs'   => 'EPSG:4326',
            'Content-Crs'  => 'EPSG:4326',
        ];
        $guzzleConfig = [
            // Base URI is used with relative requests
            'http_errors' => false,
            //'base_uri' => 'https://wrc.zaakonline.nl/applications/536bfb73-63a5-4719-b535-d835607b88b2/',
            // You can set any number of default request options.
            'timeout'  => 4000.0,
            // To work with NLX we need a couple of default headers
            'headers' => $headers,
            // Do not check certificates
            'verify' => false,
        ];
        $client = new Client($guzzleConfig);

        $response = $client->post($attachment->getUri().'/render', [
            'body'    => json_encode($attachment->getResources()),
            'headers' => $headers,
            'sink'    => $file,
        ]);

        fclose($file);

        return $filename;
    }
}
