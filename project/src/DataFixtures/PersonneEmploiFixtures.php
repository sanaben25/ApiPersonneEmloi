<?php

namespace App\DataFixtures;

use App\Entity\Emploi;
use App\Entity\Personne;
use App\Entity\PersonneEmploi;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class PersonneEmploiFixtures extends Fixture implements DependentFixtureInterface
{
    public function getDependencies()
    {
        return [
            PersonneFixtures::class,
            EmploiFixtures::class
        ];
    }

    public function load(ObjectManager $manager)
    {
        // Retrieve existing persons from the database
        $persons = $manager->getRepository(Personne::class)->findAll();
        $emploies = $manager->getRepository(Emploi::class)->findAll();

        foreach($emploies as $emploie){
            for($i=1; $i<= 10; ++$i){
                $PersonneEmploi =new PersonneEmploi();

                $PersonneEmploi->setPosteOccupe('Post '.$i);
                $PersonneEmploi->setDateDebut(new \DateTime('2022-01-01'));
                $PersonneEmploi->setDateFin(new \DateTime('2022-12-31'));
                $PersonneEmploi->setEmploi($emploie);


                    shuffle($persons);
                    foreach (array_slice($persons, 0, 3) as $person) {
                        $PersonneEmploi->setPersonne($person);
                    }
                    $manager->persist($PersonneEmploi);
                }
               
        }

        // Flush all the objects to the database
        $manager->flush();
    }
}
