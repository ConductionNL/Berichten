<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\MaxDepth;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * A  message to be send to a spefic recipient or list troug a message service.
 *
 * @ApiResource(
 *     normalizationContext={"groups"={"read"}, "enable_max_depth"=true},
 *     denormalizationContext={"groups"={"write"}, "enable_max_depth"=true}
 * )
 * @ORM\Entity(repositoryClass="App\Repository\MessageRepository")
 */
class Message
{
    /**
     * @var UuidInterface The UUID identifier of this resource
     *
     * @example e2984465-190a-4562-829e-a8cca81aa35d
     *
     * @Assert\Uuid
     * @Groups({"read"})
     * @ORM\Id
     * @ORM\Column(type="uuid", unique=true)
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class="Ramsey\Uuid\Doctrine\UuidGenerator")
     */
    private $id;

    /**
     * @var string Either a contact component person or contact list that will recieve this message
     *
     * @example https://cc.zaakonline.nl/people/06cd0132-5b39-44cb-b320-a9531b2c4ac7
     *
     * @Assert\Url
     * @Assert\Length(
     *      max = 255
     * )
     * @Groups({"read", "write"})
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $reciever;

    /**
     * @var string Either a contact component person, or wrc application that sends this message
     *
     * @example https://cc.zaakonline.nl/people/06cd0132-5b39-44cb-b320-a9531b2c4ac7
     *
     * @Assert\Url
     * @Assert\Length(
     *      max = 255
     * )
     * @Groups({"read", "write"})
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $sender;

    /**
     * @var string The webresource template object (from wrc) that is used as content for this message
     *
     * @example https://wrc.zaakonline.nl/templates/013276cc-1483-46b4-ad5b-1cba5acf6d9f
     *
     * @Assert\Url
     * @Assert\Length(
     *      max = 255
     * )
     * @Groups({"read", "write"})
     * @ORM\Column(type="string", length=255)
     */
    private $content;
    
    /**     
     * @Groups({"read", "write"})
     * @ORM\Column(type="json", nullable=true)
     */
    private $data = [];

    /**
     * @var string The current status of this message
     *
     * @example concept
     *
     * @Assert\Choice({"concept", "queued", "sending", "send", "delivered"})
     * @Assert\Length(
     *      max = 255
     * )
     *
     * @Groups({"read", "write"})
     * @ORM\Column(type="string", length=255)
     */
    private $status;
    
    /**
     * @var $serviceId The id of this message with the message service
     *
     * @example 013276cc-1483-46b4-ad5b-1cba5acf6d9f
     *
     * @Assert\Length(
     *      max = 255
     * )
     * @Groups({"read"})
     * @ORM\Column(name="external_service_id", type="string", length=255, nullable=true)
     */
    private $externalServiceId;
    /**
     * @var DateTime $send The moment this message was send
     *
     * @Assert\DateTime
     * @Groups({"read"})
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $send;
    
    /**
     * @var Service $service The service used to send this message
     * 
     * @MaxDepth(1)
     * @Groups({"read", "write"})
     * @ORM\ManyToOne(targetEntity="App\Entity\Service", inversedBy="messages")
     * @ORM\JoinColumn(nullable=false)
     */
    private $service;
    
    /**
     * @var Datetime $dateCreated The moment this request was created
     *
     * @Assert\DateTime
     * @Groups({"read"})
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateCreated;
    
    /**
     * @var Datetime $dateModified  The moment this request last Modified
     *
     * @Assert\DateTime
     * @Groups({"read"})
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateModified;

    public function getId(): ?string
    {
        return $this->id;
    }

    public function setId(string $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getReciever(): ?string
    {
        return $this->reciever;
    }

    public function setReciever(?string $reciever): self
    {
        $this->reciever = $reciever;

        return $this;
    }

    public function getSender(): ?string
    {
        return $this->sender;
    }

    public function setSender(?string $sender): self
    {
        $this->sender = $sender;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }
    
    public function getData(): ?array
    {
    	return $this->data;
    }
    
    public function setData(?array $data): self
    {
    	$this->data = $data;
    	
    	return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getService(): ?Service
    {
        return $this->service;
    }

    public function setService(?Service $service): self
    {
        $this->service = $service;

        return $this;
    }    
    
    public function getExternalServiceId(): ?string
    {
    	return $this->externalServiceId;
    }
    
    public function setExternalServiceId(string $externalServiceId): self
    {
    	$this->externalServiceId= $externalServiceId;
    	
    	return $this;
    }
        
    public function getSend(): ?\DateTimeInterface
    {
    	return $this->send;
    }
    
    public function setSend(\DateTimeInterface  $send): self
    {
    	$this->send = $send;
    	
    	return $this;
    }
    
    public function getDateCreated(): ?\DateTimeInterface
    {
    	return $this->dateCreated;
    }
    
    public function setDateCreated(\DateTimeInterface $dateCreated): self
    {
    	$this->dateCreated= $dateCreated;
    	
    	return $this;
    }
    
    public function getDateModified(): ?\DateTimeInterface
    {
    	return $this->dateModified;
    }
    
    public function setDateModified(\DateTimeInterface $dateModified): self
    {
    	$this->dateModified = $dateModified;
    	
    	return $this;
    }
}
