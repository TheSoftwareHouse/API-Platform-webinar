<?php

namespace App\DataFixtures;


use App\Entity\SoftwareApplication;
use App\DataFixtures\PersonnelReference as Ref;
use App\DataFixtures\PersonnelFixture as Personnel;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;

class ProjectFixture extends Fixture implements DependentFixtureInterface
{

    /** @var Generator */
    private $generator;

    /** @var array */
    private $indexes = [];

    /**
     * ProjectFixture constructor.
     */
    public function __construct()
    {
        $this->generator = Factory::create('pl');
        $this->indexes = [
            Ref::JR_PREFIX   => 0,
            Ref::DEV_PREFIX  => 0,
            Ref::SR_PREFIX   => 0,
            Ref::ARCH_PREFIX => 0,
            Ref::PM_PREFIX   => 0,
        ];
    }


    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $johnsonAndSon = $this->createJohnsonAndSon();
        $trainCorp = $this->createTrainCorp();

        $manager->persist($johnsonAndSon);
        $manager->persist($trainCorp);

        $manager->flush();
    }

    private function getPersonnel(string $prefix, int $countCap, int $count): array
    {
        $personnel = [];
        $startIndex = $this->indexes[$prefix];
        $endIndex = $startIndex + $count;
        for ($i = $startIndex; $i < $endIndex; $i++) {
            $orderNumber = ($startIndex + $i) % $countCap;
            $personnel[] = $this->getReference((string) PersonnelReference::create($prefix, $orderNumber));
        }

        $this->indexes[$prefix] = $endIndex;

        return $personnel;
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
            PersonnelFixture::class,
        ];
    }

    /**
     * @return array
     */
    private function createJohnsonAndSon(): SoftwareApplication
    {
        $johnsonAndSon = new SoftwareApplication();
        $johnsonAndSon->setIdentifier('P-001');
        $johnsonAndSon->setDescription($this->generator->realText);
        $johnsonAndSon->setFeatureList($this->generator->text);
        $johnsonAndSon->setName('Johnson & Son');
        $johnsonAndSon->setApplicationCategory('financial');

        $johnsonPersonnel = array_merge(
            $this->getPersonnel(Ref::JR_PREFIX, Personnel::JR_COUNT, 2),
            $this->getPersonnel(Ref::DEV_PREFIX, Personnel::DEV_COUNT, 4),
            $this->getPersonnel(Ref::SR_PREFIX, Personnel::SR_COUNT, 1),
            $this->getPersonnel(Ref::ARCH_PREFIX, Personnel::ARCH_COUNT, 1)
        );

        foreach ($johnsonPersonnel as $person) {
            $johnsonAndSon->addAuthor($person);
        }

        $johnsonProjectManager = $this->getPersonnel(Ref::PM_PREFIX, Personnel::PM_COUNT, 1);
        $johnsonAndSon->addAccountablePerson(reset($johnsonProjectManager));

        return $johnsonAndSon;
    }

    /**
     * @return SoftwareApplication
     */
    private function createTrainCorp(): SoftwareApplication
    {
        $trainCorp = new SoftwareApplication();
        $trainCorp->setIdentifier('P-002');
        $trainCorp->setDescription($this->generator->realText);
        $trainCorp->setFeatureList($this->generator->text);
        $trainCorp->setName('Train-corp');
        $trainCorp->setApplicationCategory('transport');

        $corpPersonnel = array_merge(
            $this->getPersonnel(Ref::DEV_PREFIX, Personnel::DEV_COUNT, 2),
            $this->getPersonnel(Ref::SR_PREFIX, Personnel::SR_COUNT, 1),
            $this->getPersonnel(Ref::ARCH_PREFIX, Personnel::ARCH_COUNT, 1)
        );

        foreach ($corpPersonnel as $person) {
            $trainCorp->addAuthor($person);
        }

        $corpProjectManager = $this->getPersonnel(Ref::PM_PREFIX, Personnel::PM_COUNT, 1);
        $trainCorp->addAccountablePerson(reset($corpProjectManager));
        return $trainCorp;
    }
}
