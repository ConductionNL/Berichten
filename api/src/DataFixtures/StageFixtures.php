<?php

namespace App\DataFixtures;

use App\Entity\SendList;
use App\Entity\Service;
use Conduction\CommonGroundBundle\Service\CommonGroundService;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Ramsey\Uuid\Uuid;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class StageFixtures extends Fixture implements DependentFixtureInterface
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

    public function getDependencies()
    {
        return [
            ZuiddrechtFixtures::class,
        ];
    }

    public function load(ObjectManager $manager)
    {
        if (
            !$this->params->get('app_build_all_fixtures') &&
            $this->params->get('app_domain') != 'zuiddrecht.nl' && strpos($this->params->get('app_domain'), 'zuiddrecht.nl') == false &&
            $this->params->get('app_domain') != 'zuid-drecht.nl' && strpos($this->params->get('app_domain'), 'zuid-drecht.nl') == false &&
            $this->params->get('app_domain') != 'conduction.academy' && strpos($this->params->get('app_domain'), 'conduction.academy') == false
        ) {
            return false;
        }

        $id = Uuid::fromString('e0c98e7a-7462-448c-9b25-7d23b81c7190');
        $service = new Service();
        $service->setType('mailer');
        $service->setOrganization('https://dev.zuid-drecht.nl/api/v1/wrc/organizations/4d1eded3-fbdf-438f-9536-8747dd8ab591');
        $service->setAuthorization('mailgun+api://!changeme!:mail.conduction.academy@api.eu.mailgun.net');
        $manager->persist($service);
        $service->setId($id);
        $manager->persist($service);

        $manager->flush();

        $id = Uuid::fromString('8b929e53-1e16-4e59-a254-6af6b550bd08');
        $newsLetterList = new SendList();
        $newsLetterList->setName('Newsletter');
        $newsLetterList->setDescription('Newsletter for Academy');
        $newsLetterList->setMail(true);
        $newsLetterList->setOrganization('https://dev.zuid-drecht.nl/api/v1/wrc/organizations/4d1eded3-fbdf-438f-9536-8747dd8ab591');
        $manager->persist($newsLetterList);
        $newsLetterList->setId($id);
        $manager->persist($newsLetterList);

        $manager->flush();
    }
}
