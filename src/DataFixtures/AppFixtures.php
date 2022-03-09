<?php

namespace App\DataFixtures;

use App\Entity\Campus;
use App\Entity\City;
use App\Entity\Location;
use App\Entity\Outing;
use App\Entity\Participant;
use App\Entity\State;
use App\Repository\CampusRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Faker\Generator;
use Faker\Factory;

class AppFixtures extends Fixture
{

    private ObjectManager $manager;
    private UserPasswordHasherInterface $hasher;
    private Generator $faker;


    public function load(ObjectManager $manager): void
    {
        $this->manager = $manager;
        $this->addParticipants();
        $this->addLocations();
        $this->addOutings();

    }

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->faker = Factory::create('fr_FR');
        $this->hasher = $passwordHasher;
    }

    public function addParticipants()
    {
        $campusTab = $this->manager->getRepository(Campus::class)->findAll();

        for ($i = 0; $i < 7; $i++) {

            $participant = new Participant();
            $participant->setName($this->faker->name)
                ->setFirstName($this->faker->firstName)
                ->setEmail($this->faker->email)
                ->setPassword($this->hasher->hashPassword($participant, '123'))
                ->setActive(true)
                ->setPseudo($this->faker->userName)
                ->setCampus($campusTab[$i]);

            $this->manager->persist($participant);
        }
        $this->manager->flush();


        //$users = $this->manager->getRepository(User::class)->findAll();
    }

    public function addLocations()
    {
        $cityTab = $this->manager->getRepository(City::class)->findAll();

        for ($i = 0; $i < 6; $i++) {

            $location = new Location();
            $location->setName($this->faker->streetName)
                ->setAddress($this->faker->streetAddress)
                ->setCity($cityTab[$i]);

            $this->manager->persist($location);
        }
        $this->manager->flush();


        //$users = $this->manager->getRepository(User::class)->findAll();
    }

    public function addOutings()
    {
        $locationTab = $this->manager->getRepository(Location::class)->findAll();
        $participantTab = $this->manager->getRepository(Participant::class)->findAll();
        $stateTab = $this->manager->getRepository(State::class)->findAll();
        $campusTab = $this->manager->getRepository(Campus::class)->findAll();

        for ($i = 0; $i < 6; $i++) {

            $date = date_create('2050-01-01');
            $outing= new Outing();
            $outing->setName($this->faker->company)
                ->setDateTimeStartOuting($this->faker->dateTime($max ='now', $timezone = null))
                ->setDuration(90)
                ->setRegistrationDeadLine($date)
                ->setMaxRegistrations(10)
                ->setDescription($this->faker->text(200))
                ->setState($stateTab[$i])
                ->setLocation($locationTab[$i])
                ->setOrganizer($participantTab[$i])
                ->setCampus($campusTab[$i]);

            $this->manager->persist($outing);
        }
        $this->manager->flush();


        //$users = $this->manager->getRepository(User::class)->findAll();
    }



}
