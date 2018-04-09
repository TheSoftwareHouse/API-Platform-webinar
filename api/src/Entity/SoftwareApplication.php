<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * A software application.
 *
 * @see http://schema.org/SoftwareApplication Documentation on Schema.org
 *
 * @ORM\Entity
 * @ApiResource(
 *     iri="http://schema.org/SoftwareApplication",
 *     collectionOperations={
 *          "get"={"normalization_context"={"groups"={"people_list"}}},
 *     },
 *     itemOperations={
 *          "get"={"normalization_context"={"groups"={"person_single_get"}}},
 *     })
 */
class SoftwareApplication
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
     * @var string|null the name of the item
     *
     * @ORM\Column(type="text", nullable=true)
     * @ApiProperty(iri="http://schema.org/name")
     * @Groups({"project_summary", "project_detail"})
     */
    private $name;

    /**
     * @var string|null a description of the item
     *
     * @ORM\Column(type="text", nullable=true)
     * @ApiProperty(iri="http://schema.org/description")
     */
    private $description;

    /**
     * @var string|null The identifier property represents any kind of identifier for any kind of \[\[Thing\]\], such as ISBNs, GTIN codes, UUIDs etc. Schema.org provides dedicated properties for representing many of these, either as textual strings or as URL (URI) links. See \[background notes\](/docs/datamodel.html#identifierBg) for more details.
     *
     * @ORM\Column(type="text", nullable=true)
     * @ApiProperty(iri="http://schema.org/identifier")
     * @Groups({"project_summary", "project_detail"})
     */
    private $identifier;

    /**
     * @var string|null features or modules provided by this application (and possibly required by other applications)
     *
     * @ORM\Column(type="text", nullable=true)
     * @ApiProperty(iri="http://schema.org/featureList")
     * @Groups({"project_detail"})
     */
    private $featureList;

    /**
     * @var string|null Type of software application, e.g. 'Game, Multimedia'.
     *
     * @ORM\Column(type="text", nullable=true)
     * @ApiProperty(iri="http://schema.org/applicationCategory")
     * @Groups({"project_detail"})
     */
    private $applicationCategory;

    /**
     * @var Collection<Person>|null The author of this content or rating. Please note that author is special in that HTML 5 provides a special mechanism for indicating authorship via the rel tag. That is equivalent to this and may be used interchangeably.
     *
     * @ORM\ManyToMany(targetEntity="App\Entity\Person")
     * @ORM\JoinTable(name="software_application_author")
     * @ApiProperty(iri="http://schema.org/author")
     * @Groups({"project_summary"})
     */
    private $authors;

    /**
     * @var Collection<Person>|null specifies the Person that is legally accountable for the CreativeWork
     *
     * @ORM\ManyToMany(targetEntity="App\Entity\Person")
     * @ORM\JoinTable(name="software_application_accountable_person")
     * @ApiProperty(iri="http://schema.org/accountablePerson")
     * @Groups({"project_summary"})
     */
    private $accountablePeople;

    public function __construct()
    {
        $this->authors = new ArrayCollection();
        $this->accountablePeople = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setIdentifier(?string $identifier): void
    {
        $this->identifier = $identifier;
    }

    public function getIdentifier(): ?string
    {
        return $this->identifier;
    }

    public function setFeatureList(?string $featureList): void
    {
        $this->featureList = $featureList;
    }

    public function getFeatureList(): ?string
    {
        return $this->featureList;
    }

    public function setApplicationCategory(?string $applicationCategory): void
    {
        $this->applicationCategory = $applicationCategory;
    }

    public function getApplicationCategory(): ?string
    {
        return $this->applicationCategory;
    }

    public function addAuthor(Person $author): void
    {
        $this->authors[] = $author;
    }

    public function removeAuthor(Person $author): void
    {
        $this->authors->removeElement($author);
    }

    public function getAuthors(): Collection
    {
        return $this->authors;
    }

    public function addAccountablePerson(Person $accountablePerson): void
    {
        $this->accountablePeople[] = $accountablePerson;
    }

    public function removeAccountablePerson(Person $accountablePerson): void
    {
        $this->accountablePeople->removeElement($accountablePerson);
    }

    public function getAccountablePeople(): Collection
    {
        return $this->accountablePeople;
    }
}
