<?php
declare(strict_types=1);

namespace App\DataFixtures;


use App\Entity\Role;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\BadMethodCallException;
use Doctrine\Common\Persistence\ObjectManager;

class RoleFixture extends Fixture
{
    public const REF_JUNIOR = 'junior';
    public const REF_DEVELOPER = 'developer';
    public const REF_SENIOR = 'senior';
    public const REF_ARCH_EXP = 'arch-exp';
    public const REF_PM = 'pm';

    /** @var ObjectManager */
    private $manager = null;

    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $this->manager = $manager;

        $junior = $this->createRole(self::REF_JUNIOR, 'Junior Developer');
        $developer = $this->createRole(self::REF_DEVELOPER, 'Developer');
        $senior = $this->createRole(self::REF_SENIOR, 'Senior Developer');
        $analytic = $this->createRole(self::REF_ARCH_EXP, 'Field expert');
        $projectManager = $this->createRole(self::REF_PM, 'Project manager');

        $manager->flush();

        $this->addReference(static::REF_JUNIOR, $junior);
        $this->addReference(static::REF_DEVELOPER, $developer);
        $this->addReference(static::REF_SENIOR, $senior);
        $this->addReference(static::REF_ARCH_EXP, $analytic);
        $this->addReference(static::REF_PM, $projectManager);

    }

    private function createRole(string $id, string $label): Role
    {
        $role = new Role();
        $role->setRoleName($label);
        $role->setIdentifier($id);
        $this->manager->persist($role);

        return $role;
    }

}
