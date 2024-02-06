<?php

namespace App\DataFixtures;

use App\Entity\Emploi;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;


class EmploiFixtures extends Fixture
{

    public function load(ObjectManager $manager)
    {
        // Retrieve existing persons from the database

            for($i=1; $i<= 10; ++$i){
            $emploi = new Emploi();
            $emploi->setNomEntreprise('campany '.$i);
            $manager->persist($emploi);

            $this->addReference('emploi' . $i, $emploi);
        }

        // Flush all the objects to the database
        $manager->flush();
    }
}
