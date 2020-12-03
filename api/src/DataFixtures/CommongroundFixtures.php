<?php

namespace App\DataFixtures;

use App\Entity\Application;
use App\Entity\Image;
use App\Entity\Organization;
use App\Entity\SendList;
use App\Entity\Style;
use Conduction\CommonGroundBundle\Service\CommonGroundService;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Ramsey\Uuid\Uuid;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class CommongroundFixtures extends Fixture
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
        // Lets make sure we only run these fixtures on larping enviroment
        if (
            !$this->params->get('app_build_all_fixtures') &&
            $this->params->get('app_domain') != 'commonground.nu' && strpos($this->params->get('app_domain'), 'commonground.nu') == false &&
            $this->params->get('app_domain') != 'zuid-drecht.nl' && strpos($this->params->get('app_domain'), 'zuid-drecht.nl') == false) {
            return false;
        }

        $id = Uuid::fromString('30a1ccce-6ed5-4647-af04-d319b292e232');
        $service = new Service();
        $service->setType('mailer');
        $service->setOrganization($this->commonGroundService->cleanUrl(['component'=>'wrc', 'type'=>'organizations', 'id'=>'073741b3-f756-4767-aa5d-240f167ca89d']));
        $service->setAuthorization('mailgun+api://!changeme!:mail.commonground.nu@api.eu.mailgun.net');
        $manager->persist($service);
        $service->setId($id);
        $manager->persist($service);

        $manager->flush();

        $id = Uuid::fromString('70061485-7f3b-4b12-8755-30021faea80c');
        $newsLetterList = new SendList();
        $newsLetterList->setName('Newsletter');
        $newsLetterList->setDescription('Newsletter for Commonground.nu');
        $newsLetterList->setMail(true);
        $newsLetterList->setOrganization('https://dev.zuid-drecht.nl/api/v1/wrc/organizations/073741b3-f756-4767-aa5d-240f167ca89d');
        $manager->persist($newsLetterList);
        $newsLetterList->setId($id);
        $manager->persist($newsLetterList);

        $manager->flush();
    }
}
