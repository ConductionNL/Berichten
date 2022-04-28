<?php

namespace App\DataFixtures;

use App\Entity\Email;
use App\Entity\Person;
use App\Entity\Service;
use Conduction\CommonGroundBundle\Service\CommonGroundService;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Ramsey\Uuid\Uuid;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class TaalhuizenFixtures extends Fixture
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
//        if (
//            !$this->params->get('app_build_all_fixtures') &&
//            $this->params->get('app_domain') != 'taalhuizen-bisc.commonground.nu' && strpos($this->params->get('app_domain'), 'taalhuizen-bisc.commonground.nu') == false &&
//            $this->params->get('app_domain') != 'acceptatietaalhuizen-bisc.commonground.nu' && strpos($this->params->get('app_domain'), 'acceptatietaalhuizen-bisc.commonground.nu') == false
//        ) {
//            return false;
//        }

        $id = Uuid::fromString('088f7b63-1693-4f27-9911-dadcb83ae5db');
        $service = new Service();
        $service->setType('mailer');
        $service->setOrganization($this->commonGroundService->cleanUrl(['component'=>'wrc', 'type'=>'organizations', 'id'=>'073741b3-f756-4767-aa5d-240f167ca89d']));
        $service->setAuthorization('mailgun+api://!changeme!:taalhuizen-bisc.commonground.nu@api.eu.mailgun.net');
        $manager->persist($service);
        $service->setId($id);
        $manager->persist($service);

        $manager->flush();
    }
}
