<?php
declare(strict_types=1);

namespace App\DataFixtures;


use App\Entity\QuantitativeValue;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class HourRateFixture extends Fixture
{
    public const LOW = 'low-rate';
    public const STANDARD = 'standard-rate';
    public const HIGH = 'high-rate';
    public const PREMIUM = 'premium-rate';

    /** @var ObjectManager */
    private $manager;

    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $this->manager = $manager;

        $low = $this->createRate(15.0, 25.0);
        $standard = $this->createRate(25.0, 32.0);
        $high = $this->createRate(40.0, 50.0);
        $premium = $this->createRate(100, 200);

        $manager->flush();

        $this->addReference(static::LOW, $low);
        $this->addReference(static::STANDARD, $standard);
        $this->addReference(static::HIGH, $high);
        $this->addReference(static::PREMIUM, $premium);
    }

    private function createRate(float $lowBoundary, float $highBoundary)
    {
        $rate = new QuantitativeValue();
        $rate->setMinValue($lowBoundary);
        $rate->setMaxValue($highBoundary);

        $this->manager->persist($rate);

        return $rate;
    }
}
