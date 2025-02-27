<?php

namespace App\DataFixtures;

use App\Entity\Service;
use Conduction\CommonGroundBundle\Service\CommonGroundService;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Ramsey\Uuid\Uuid;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class LarpingFixtures extends Fixture
{
    private $params;

    /**
     * @var CommonGroundService
     */
    public function __construct(ParameterBagInterface $params, CommonGroundService $commonGroundService)
    {
        $this->params = $params;
        $this->commonGroundService = $commonGroundService;
    }

    public function load(ObjectManager $manager)
    {
        // Lets make sure we only run these fixtures on larping enviroment
        if (
            $this->params->get('app_domain') != 'larping.eu' && strpos($this->params->get('app_domain'), 'larping.eu') == false
        ) {
            return false;
        }

        $id = Uuid::fromString('dfb46b45-0737-4500-b8f9-2f791913c8ad');
        $service = new Service();
        $service->setType('mailer');
        $service->setOrganization('https://wrc.larping.eu/templates/cc7d0c70-bb59-4d85-9845-863e896e6ee9');
        $service->setAuthorization('mailgun+api://key-PLACEHOLDER:larping.eu@default');
        $manager->persist($service);
        $service->setId($id);
        $manager->persist($service);

        $manager->flush();
    }
}
