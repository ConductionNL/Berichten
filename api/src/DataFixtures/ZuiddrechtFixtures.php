<?php

namespace App\DataFixtures;

use App\Entity\Service;
use Conduction\CommonGroundBundle\Service\CommonGroundService;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Ramsey\Uuid\Uuid;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class ZuiddrechtFixtures extends Fixture
{
    private $params;
    /**
     * @var CommonGroundService
     */
    private $commonGroundService;

    public function __construct(ParameterBagInterface $params, CommonGroundService $commonGroundService)
    {
        $this->params = $params;
        $this->commonGroundService = $commonGroundService;
    }

    public function load(ObjectManager $manager)
    {
        if (
            !$this->params->get('app_build_all_fixtures') &&
            $this->params->get('app_domain') != 'zuiddrecht.nl' && strpos($this->params->get('app_domain'), 'zuiddrecht.nl') == false &&
            $this->params->get('app_domain') != 'zuid-drecht.nl' && strpos($this->params->get('app_domain'), 'zuid-drecht.nl') == false
        ) {
            return false;
        }

        $id = Uuid::fromString('1541d15b-7de3-4a1a-a437-80079e4a14e0');
        $service = new Service();
        $service->setType('mailer');
        $service->setOrganization('https://wrc.zuid-drecht.nl/organizations/4d1eded3-fbdf-438f-9536-8747dd8ab591');
        $service->setAuthorization('mailgun+api://!changeme!:mail.zuid-drecht.nl@api.eu.mailgun.net');
        $manager->persist($service);
        $service->setId($id);
        $manager->persist($service);

        $manager->flush();
    }
}
