<?php
declare(strict_types=1);

namespace App\DataFixtures;


use App\DataFixtures\PersonnelReference as Ref;
use App\Entity\Person;
use App\Entity\QuantitativeValue;
use App\Entity\Role;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;

class PersonnelFixture extends Fixture implements DependentFixtureInterface
{
    public const JR_COUNT = 10;
    public const DEV_COUNT = 20;
    public const SR_COUNT = 8;
    public const ARCH_COUNT = 2;
    public const PM_COUNT = 4;

    /** @var Generator */
    private $generator;

    /** @var ObjectManager */
    private $manager;

    /**
     * PersonnelFixture constructor.
     */
    public function __construct()
    {
        $this->generator = Factory::create('pl');
    }


    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $this->manager = $manager;

        $juniors = $this->create(RoleFixture::REF_JUNIOR, static::JR_COUNT, HourRateFixture::LOW);
        $developers = $this->create(RoleFixture::REF_DEVELOPER, static::DEV_COUNT, HourRateFixture::STANDARD);
        $seniors = $this->create(RoleFixture::REF_SENIOR, static::SR_COUNT, HourRateFixture::HIGH);
        $experts = $this->create(RoleFixture::REF_ARCH_EXP, static::ARCH_COUNT, HourRateFixture::PREMIUM);
        $projectManagers = $this->create(RoleFixture::REF_PM, static::PM_COUNT, HourRateFixture::STANDARD);

        $manager->flush();

        $this->referencePersonnel($juniors, Ref::JR_PREFIX);
        $this->referencePersonnel($developers, Ref::DEV_PREFIX);
        $this->referencePersonnel($seniors, Ref::SR_PREFIX);
        $this->referencePersonnel($experts, Ref::ARCH_PREFIX);
        $this->referencePersonnel($projectManagers, Ref::PM_PREFIX);

    }

    private function create(string $role, int $count, string $rate): array
    {
        $personnel = [];
        for ($i = 0; $i < $count; $i++) {
            $person = new Person();
            $person->setGivenName($this->generator->firstName);
            $person->setFamilyName($this->generator->lastName);
            $person->setBirthDate($this->generator->dateTime('18 years ago'));
            $person->setEmail($this->generator->companyEmail);
            $person->setTelephone($this->generator->phoneNumber);
            $person->setJobTitle("Scrum Developer!");
            $this->applyRate($rate, $person);
            $this->applyRole($role, $person);

            $this->manager->persist($person);
            $personnel[] = $person;
        }

        return $personnel;
    }

    public function referencePersonnel(array $personnel, string $prefix): void
    {
        foreach ($personnel as $index => $person) {
            $reference = (string) PersonnelReference::create($prefix, $index);
            $this->addReference($reference, $person);
        }
    }

    /**
     * This method must return an array of fixtures classes
     * on which the implementing class depends on
     *
     * @return array
     */
    function getDependencies()
    {
        return [
            RoleFixture::class,
            HourRateFixture::class,
        ];
    }

    /**
     * @param string $rate
     * @param Person $person
     */
    private function applyRate(string $rate, Person $person): void
    {
        /** @var QuantitativeValue $rate */
        $rate = $this->getReference($rate);
        $person->addRate($rate);
    }

    /**
     * @param string $rate
     * @param Person $person
     */
    private function applyRole(string $role, Person $person): void
    {
        /** @var Role $role */
        $role = $this->getReference($role);
        $person->addHasOccupation($role);
    }
}
