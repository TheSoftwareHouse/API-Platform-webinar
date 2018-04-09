<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * A point value or interval for product characteristics and other purposes.
 *
 * @see http://schema.org/QuantitativeValue Documentation on Schema.org
 *
 * @ORM\Entity
 * @ApiResource(
 *     iri="http://schema.org/QuantitativeValue",
 *     collectionOperations={
 *          "get"
 *     },
 *     itemOperations={
 *          "get"
 *     })
 */
class QuantitativeValue
{
    /**
     * @var int|null
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var float|null the lower value of some characteristic or property
     *
     * @ORM\Column(type="float", nullable=true)
     * @ApiProperty(iri="http://schema.org/minValue")
     * @Groups({"person_single_get", "project_summary"})
     */
    private $minValue;

    /**
     * @var float|null the upper value of some characteristic or property
     *
     * @ORM\Column(type="float", nullable=true)
     * @ApiProperty(iri="http://schema.org/maxValue")
     * @Groups({"person_single_get", "project_summary"})
     */
    private $maxValue;

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param float|null $minValue
     */
    public function setMinValue($minValue): void
    {
        $this->minValue = $minValue;
    }

    /**
     * @return float|null
     */
    public function getMinValue()
    {
        return $this->minValue;
    }

    /**
     * @param float|null $maxValue
     */
    public function setMaxValue($maxValue): void
    {
        $this->maxValue = $maxValue;
    }

    /**
     * @return float|null
     */
    public function getMaxValue()
    {
        return $this->maxValue;
    }
}
