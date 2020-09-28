<?php

namespace App\DataFixtures;

use App\Entity\Service;
use Conduction\CommonGroundBundle\Service\CommonGroundService;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Ramsey\Uuid\Uuid;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class AppFixtures extends Fixture
{
    private $params;
    private $commonGroundService;

    public function __construct(ParameterBagInterface $params, CommonGroundService $commonGroundService)
    {
        $this->params = $params;
        $this->commonGroundService = $commonGroundService;
    }

    public function load(ObjectManager $manager)
    {
        if (strpos($this->params->get('app_domain'), 'larping.eu') != false || $this->params->get('app_domain') == 'larping.eu') {
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
        if (
            strpos($this->params->get('app_domain'), 'huwelijksplanner.online') != false ||
            $this->params->get('app_domain') == 'huwelijksplanner.online' ||
            strpos($this->params->get('app_domain'), 'utrecht.commmonground.nu') != false ||
            $this->params->get('app_domain') == 'utrecht.commmonground.nu'

        ) {
            $id = Uuid::fromString('a8b29815-7fdd-45a1-9951-aab9462b4457');
            $service = new Service();
            $service->setType('mailer');
            $service->setOrganization('https://wrc.huwelijksplanner.online/organizations/68b64145-0740-46df-a65a-9d3259c2fec8');
            $service->setAuthorization('mailgun+api://key-PLACEHOLDER:mail.huwelijksplanner.online@default');
            $manager->persist($service);
            $service->setId($id);
            $manager->persist($service);

            $manager->flush();
        }
        if (
            strpos($this->params->get('app_domain'), 'zuid-drecht.nl') != false ||
            $this->params->get('app_domain') == 'zuid-drecht.nl'

        ) {
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
        if (
            strpos($this->params->get('app_domain'), 'zuid-drecht.nl') != false ||
            $this->params->get('app_domain') == 'zuid-drecht.nl' ||
            strpos($this->params->get('app_domain'), 'checking.nu') != false ||
            $this->params->get('app_domain') == 'checking.nu'

        ) {
            $id = Uuid::fromString('eb7ffa01-4803-44ce-91dc-d4e3da7917da');
            $service = new Service();
            $service->setType('mailer');
            $service->setOrganization('https://dev.zuid-drecht.nl/api/v1/wrc/organizations/c571bdad-f34c-4e24-94e7-74629cfaccc9');
            $service->setAuthorization('mailgun+api://!changeme!:mail.zuid-drecht.nl@api.eu.mailgun.net');
            $manager->persist($service);
            $service->setId($id);
            $manager->persist($service);

            $manager->flush();
        }
        if (
            strpos($this->params->get('app_domain'), 'westfriesland.commonground.nu') != false ||
            $this->params->get('app_domain') == 'westfriesland.commonground.nu'

        ) {
            $id = Uuid::fromString('ab0d332d-9c8c-490d-bcfb-2607d8690a03');
            $service = new Service();
            $service->setType('mailer');
            $service->setOrganization($this->commonGroundService->cleanUrl(['component'=>'wrc', 'type'=>'organizations', 'id'=>'d280c4d3-6310-46db-9934-5285ec7d0d5e']));
            $service->setAuthorization('mailgun+api://!changeme!:mail.westfriesland.commonground.nu@api.eu.mailgun.net');
            $manager->persist($service);
            $service->setId($id);
            $manager->persist($service);

            $manager->flush();
        }
    }
}
