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

    /**
     * @param ObjectManager $manager
     * @return void
     * function en charge de remplir la base de données à l'aide de fixture et faker
     * function appelée en CLI
     */
    public function load(ObjectManager $manager): void
    {
        $this->manager = $manager;
        $this->addState();
        $this->addCity();
        $this->addCampus();
        $this->addLocations();
        $this->addParticipants();
        $this->addOutings();
    }

    /**
     * @param UserPasswordHasherInterface $passwordHasher
     * function en charge de paramétrer la langue des données générées et de hasher le password
     */
    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->faker = Factory::create('fr_FR');
        $this->hasher = $passwordHasher;
    }

    /**
     * @return void
     * function en charge de générer des participants dans la base de données
     */
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
    }

    /**
     * @return void
     * function en charge de générer des lieux dans la base de données
     */
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
    }

    /**
     * @return void
     * function en charge de générer des sorties dans la base de données
     */
    public function addOutings()
    {
        $locationTab = $this->manager->getRepository(Location::class)->findAll();
        $participantTab = $this->manager->getRepository(Participant::class)->findAll();
        $stateTab = $this->manager->getRepository(State::class)->findAll();
        $campusTab = $this->manager->getRepository(Campus::class)->findAll();
        for ($i = 0; $i < 6; $i++) {
            $date = date_create('2050-01-01');
            $outing = new Outing();
            $outing->setName($this->faker->company)
                ->setDateTimeStartOuting($this->faker->dateTime($max = 'now', $timezone = null))
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
    }

    /**
     * @return void
     * function en charge de générer des campus dans la base de données
     */
    public function addCampus()
    {
        for ($i = 0; $i < 6; $i++) {
            $campus = new Campus();
            $campus->setName($this->faker->city);
            $this->manager->persist($campus);
        }
        $this->manager->flush();
    }

    /**
     * @return void
     * function en charge de générer des villes dans la base de données
     */
    public function addCity()
    {
        for ($i = 0; $i < 6; $i++) {
            $city = new City();
            $city->setName($this->faker->city);
            $city->setPostalcode((00000));
            $this->manager->persist($city);
        }
        $this->manager->flush();
    }

    /**
     * @return void
     * function en charge de générer des états dans la base de données
     */
    public function addState()
    {
        $states = ['Créée','Ouverte','Cloturée','Activité en cours', 'Passée', 'Annulée', 'Historisée'];
        for ($i = 0; $i < 7; $i++) {
            $state = new State();
            $state -> setWording($states[$i]);
            $this->manager->persist($state);
        }
        $this->manager->flush();
    }

}
