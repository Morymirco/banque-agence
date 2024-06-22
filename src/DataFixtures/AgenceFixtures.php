<?php

namespace App\DataFixtures;
// src/DataFixtures/AgenceFixtures.php

namespace App\DataFixtures;

use App\Entity\Agence;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AgenceFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        for ($i = 1; $i <= 50; $i++) {
            $agence = new Agence();
            $agence->setNom('Agence ' . $i);
            $agence->setAdresse('Adresse ' . $i);
            $agence->setCode(mt_rand(0,1000000));
            
            $manager->persist($agence);
        }

        $manager->flush();
    }
}
