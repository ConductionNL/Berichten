<?php

namespace App\DataFixtures;

use App\Entity\Service;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Ramsey\Uuid\Uuid;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class AppFixtures extends Fixture
{
    private $params;

    public function __construct(ParameterBagInterface $params)
    {
        $this->params = $params;
    }
    public function load(ObjectManager $manager)
    {

        if(!in_array("larping.eu",$this->params->get('app_domains'))){
            return false;
        }
        // $product = new Product();
        // $manager->persist($product);

        $id = Uuid::fromString('d1cc2c8c-c87d-4bb1-b468-9546b4ce29a5');
        $service = new Service();
        $service->setType("mailer");
        $service->setOrganization("https://wrc.larping.eu/templates/cc7d0c70-bb59-4d85-9845-863e896e6ee9");
        $service->setAuthorization("mailgun+api://key-PLACEHOLDER:larping.eu@default");
        $manager->persist($service);
        $service->setId($id);
        $manager->persist($service);

        $manager->flush();
    }
}
