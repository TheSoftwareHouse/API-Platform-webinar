<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\DateFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\OrderFilter;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * A person (alive, dead, undead, or fictional).
 *
 * @see http://schema.org/Person Documentation on Schema.org
 *
 * @ORM\Entity
 * @ApiResource(
 *     iri="http://schema.org/Person",
 *     collectionOperations={
 *          "get"={"normalization_context"={"groups"={"people_list"}}},
 *     },
 *     itemOperations={
 *          "get"={"normalization_context"={"groups"={"person_single_get"}}},
 *          "put"={"denormalization_context"={"groups"={"person_write"}}}
 *     }
 * )
 * @UniqueEntity("email")
 * @ApiFilter(DateFilter::class, properties={"birthDate"})
 * @ApiFilter(SearchFilter::class, properties={"familyName": "partial", "givenName": "partial", "email":"exact", "jobTitle":"exact"})
 * @ApiFilter(OrderFilter::class, properties={"id", "familyName", "givenName", "email"})
 */
class Person
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
     * @var string|null Family name. In the U.S., the last name of an Person. This can be used along with givenName instead of the name property.
     *
     * @ORM\Column(type="text", nullable=true)
     * @ApiProperty(iri="http://schema.org/familyName")
     * @Groups({"person_single_get", "project_summary", "people_list"})
     */
    private $familyName;

    /**
     * @var string|null Given name. In the U.S., the first name of a Person. This can be used along with familyName instead of the name property.
     *
     * @ORM\Column(type="text", nullable=true)
     * @ApiProperty(iri="http://schema.org/givenName")
     * @Groups({"person_single_get", "project_summary", "people_list"})
     */
    private $givenName;

    /**
     * @var \DateTimeInterface|null date of birth
     *
     * @ORM\Column(type="date", nullable=true)
     * @ApiProperty(iri="http://schema.org/birthDate")
     * @Assert\Date
     * @Groups({"person_single_get"})
     */
    private $birthDate;

    /**
     * @var string|null the telephone number
     *
     * @ORM\Column(type="text", nullable=true)
     * @ApiProperty(iri="http://schema.org/telephone")
     * @Groups({"person_single_get"})
     */
    private $telephone;

    /**
     * @var string|null email address
     *
     * @ORM\Column(type="text", nullable=true, unique=true)
     * @ApiProperty(iri="http://schema.org/email")
     * @Assert\Email
     * @Groups({"person_single_get"})
     */
    private $email;

    /**
     * @var string|null the job title of the person (for example, Financial Manager)
     *
     * @ORM\Column(type="text", nullable=true)
     * @ApiProperty(iri="http://schema.org/jobTitle")
     * @Groups({"person_single_get", "project_summary", "people_list", "person_write"})
     */
    private $jobTitle;

    /**
     * @var Collection<Role>|null
     *
     * @ORM\ManyToMany(targetEntity="App\Entity\Role")
     * @Groups({"person_single_get"})
     * @ApiSubresource
     */
    private $hasOccupations;

    /**
     * @var Collection<QuantitativeValue>|null
     *
     * @ORM\ManyToMany(targetEntity="App\Entity\QuantitativeValue")
     * @Groups({"person_single_get", "person_write"})
     * @ApiSubresource
     */
    private $rates;

    public function __construct()
    {
        $this->hasOccupations = new ArrayCollection();
        $this->rates = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setFamilyName(?string $familyName): void
    {
        $this->familyName = $familyName;
    }

    public function getFamilyName(): ?string
    {
        return $this->familyName;
    }

    public function setGivenName(?string $givenName): void
    {
        $this->givenName = $givenName;
    }

    public function getGivenName(): ?string
    {
        return $this->givenName;
    }

    public function setBirthDate(?\DateTimeInterface $birthDate): void
    {
        $this->birthDate = $birthDate;
    }

    public function getBirthDate(): ?\DateTimeInterface
    {
        return $this->birthDate;
    }

    public function setTelephone(?string $telephone): void
    {
        $this->telephone = $telephone;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setEmail(?string $email): void
    {
        $this->email = $email;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setJobTitle(?string $jobTitle): void
    {
        $this->jobTitle = $jobTitle;
    }

    public function getJobTitle(): ?string
    {
        return $this->jobTitle;
    }

    public function addHasOccupation(Role $hasOccupation): void
    {
        $this->hasOccupations[] = $hasOccupation;
    }

    public function removeHasOccupation(Role $hasOccupation): void
    {
        $this->hasOccupations->removeElement($hasOccupation);
    }

    public function getHasOccupations(): Collection
    {
        return $this->hasOccupations;
    }

    public function addRate(QuantitativeValue $rate): void
    {
        $this->rates[] = $rate;
    }

    public function removeRate(QuantitativeValue $rate): void
    {
        $this->rates->removeElement($rate);
    }

    public function getRates(): Collection
    {
        return $this->rates;
    }
}
