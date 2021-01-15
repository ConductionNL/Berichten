<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\BooleanFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\DateFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use App\Repository\SubscriberRepository;
use DateTime;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\MaxDepth;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * SendList is a way for Applications to send messages through email or phone.
 *
 * @ApiResource(
 *     normalizationContext={"groups"={"read"}, "enable_max_depth"=true},
 *     denormalizationContext={"groups"={"write"}, "enable_max_depth"=true}
 * )
 * @ORM\Entity(repositoryClass=SubscriberRepository::class)
 * @Gedmo\Loggable(logEntryClass="Conduction\CommonGroundBundle\Entity\ChangeLog")
 *
 * @ApiFilter(BooleanFilter::class)
 * @ApiFilter(OrderFilter::class)
 * @ApiFilter(DateFilter::class, strategy=DateFilter::EXCLUDE_NULL)
 * @ApiFilter(SearchFilter::class, properties={
 *     "email": "exact",
 *     "resource": "exact"
 * })
 */
class Subscriber
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
     * @var string email of the subscriber
     *
     * @example test@conduction.nl
     *
     * @Gedmo\Versioned
     * @Assert\Length(
     *      max = 255
     * )
     * @Groups({"read", "write"})
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $email;

    /**
     * @var string resource of this subscriber, for example: wac/group, uc/user or cc/person
     *
     * @example https://id-vault.com/api/v1/wac/groups/cd48e62a-0a5b-4ace-a519-01321a928dd0
     *
     * @Assert\Url
     * @Gedmo\Versioned
     * @Assert\Length(
     *      max = 255
     * )
     * @Groups({"read", "write"})
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $resource;

    /**
     * @var DateTime The moment the invite was send
     *
     * @example 20190101
     * @Groups({"read"})
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateInvited;

    /**
     * @var DateTime The moment the invite was accepted by the organization
     *
     * @example 20190101
     *
     * @Groups({"read", "write"})
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateAcceptedOrganization;

    /**
     * @var DateTime The moment the invite was accepted by the user
     *
     * @example 20190101
     *
     * @Groups({"read", "write"})
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateAcceptedUser;

    /**
     * @var Datetime The moment this proof was created
     *
     * @Groups({"read"})
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateCreated;

    /**
     * @var Datetime The moment this proof was last Modified
     *
     * @Groups({"read"})
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateModified;

    /**
     * @Groups({"read","write"})
     * @Assert\NotNull
     * @MaxDepth(1)
     * @ORM\ManyToMany(targetEntity=SendList::class, mappedBy="subscribers")
     * @ORM\JoinColumn(nullable=false)
     */
    private $sendLists;

    public function __construct()
    {
        $this->sendLists = new ArrayCollection();
    }

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function setId(Uuid $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getResource(): ?string
    {
        return $this->resource;
    }

    public function setResource(string $resource): self
    {
        $this->resource = $resource;

        return $this;
    }

    public function getDateCreated(): ?\DateTimeInterface
    {
        return $this->dateCreated;
    }

    public function setDateCreated(\DateTimeInterface $dateCreated): self
    {
        $this->dateCreated = $dateCreated;

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

    public function getDateAcceptedUser(): ?\DateTimeInterface
    {
        return $this->dateAcceptedUser;
    }

    public function setDateAcceptedUser(DateTimeInterface $dateAcceptedUser): self
    {
        $this->dateAcceptedUser = $dateAcceptedUser;

        return $this;
    }

    public function getDateAcceptedOrganization(): ?\DateTimeInterface
    {
        return $this->dateAcceptedOrganization;
    }

    public function setDateAcceptedOrganization(DateTimeInterface $dateAcceptedOrganization): self
    {
        $this->dateAcceptedOrganization = $dateAcceptedOrganization;

        return $this;
    }

    public function getDateInvited(): ?\DateTimeInterface
    {
        return $this->dateInvited;
    }

    public function setDateInvited(DateTimeInterface $dateInvited): self
    {
        $this->dateInvited = $dateInvited;

        return $this;
    }

    /**
     * @return Collection|SendList[]
     */
    public function getSendLists(): Collection
    {
        return $this->sendLists;
    }

    public function addSendList(SendList $sendList): self
    {
        if (!$this->sendLists->contains($sendList)) {
            $this->sendLists[] = $sendList;
            $sendList->addSubscriber($this);
        }

        return $this;
    }

    public function removeSendList(SendList $sendList): self
    {
        if ($this->sendLists->contains($sendList)) {
            $this->sendLists->removeElement($sendList);
            $sendList->removeSubscriber($this);
        }

        return $this;
    }
}
