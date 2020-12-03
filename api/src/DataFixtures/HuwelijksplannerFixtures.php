<?php

namespace App\DataFixtures;

use App\Entity\Application;
use App\Entity\Configuration;
use App\Entity\Image;
use App\Entity\Organization;
use App\Entity\Service;
use App\Entity\Slug;
use App\Entity\Style;
use App\Entity\Template;
use App\Entity\TemplateGroup;
use Conduction\CommonGroundBundle\Service\CommonGroundService;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Ramsey\Uuid\Uuid;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class HuwelijksplannerFixtures extends Fixture
{
    private $params;
    private $commonGroundService;

    public function __construct(ParameterBagInterface $params, CommonGroundService $commonGroundService)
    {
        $this->commonGroundService = $commonGroundService;
        $this->params = $params;
    }

    public function load(ObjectManager $manager)
    {
        // Lets make sure we only run these fixtures on larping enviroment
        if (
            !$this->params->get('app_build_all_fixtures') &&
            $this->params->get('app_domain') != 'huwelijksplanner.online' && strpos($this->params->get('app_domain'), 'huwelijksplanner.online') == false &&
            $this->params->get('app_domain') != 'utrecht.commonground.nu' && strpos($this->params->get('app_domain'), 'utrecht.commonground.nu') == false
        ) {
            return false;
        }

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
}
