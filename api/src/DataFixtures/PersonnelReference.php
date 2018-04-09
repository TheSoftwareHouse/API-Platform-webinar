<?php

namespace App\DataFixtures;


final class PersonnelReference
{
    public const JR_PREFIX = 'personnel-jr';
    public const DEV_PREFIX = 'personnel-dev';
    public const SR_PREFIX = 'personnel-sr';
    public const ARCH_PREFIX = 'personnel-arch';
    public const PM_PREFIX = 'personnel-pm';

    private $id;

    /**
     * PersonnelReference constructor.
     */
    private function __construct(string $prefix, int $index)
    {
        $this->id = sprintf('%s%s', $prefix, $index);
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->id;
    }

    public static function create(string $prefix, int $index): self
    {
        return new self($prefix, $index);
    }

}
