<?php

namespace App\DataFixtures;

use App\Entity\Personne;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class PersonneFixtures extends Fixture
{
    private array $firstNames = [
        'John', 'Jane', 'Michael', 'Emily', 'William', 'Olivia', 'James', 'Emma', 'Alexander', 'Sophia'
    ];

    private array $lastNames = [
        'Smith', 'Johnson', 'Brown', 'Taylor', 'Miller', 'Anderson', 'Clark', 'Lee', 'Thomas', 'Garcia'
    ];

    public function load(ObjectManager $manager)
    {
        // Create example Persons
        for ($i = 1; $i <= 10; $i++) {
            $person = new Personne();
            $person->setNom($this->randomElement($this->firstNames));
            $person->setPrenom($this->randomElement($this->lastNames));
            $person->setDateNaissance(new \DateTime()); // Set birthdate to current date or set it randomly

            $manager->persist($person);

            // Add a reference to use in other fixtures like job fixtures
            $this->addReference('personne' . $i, $person);
        }

        $manager->flush();
    }

    private function randomElement(array $array)
    {
        return $array[array_rand($array)];
    }

}
