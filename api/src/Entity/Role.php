<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * Represents additional information about a relationship or property. For example a Role can be used to say that a 'member' role linking some SportsTeam to a player occurred during a particular time period. Or that a Person's 'actor' role in a Movie was for some particular characterName. Such properties can be attached to a Role entity, which is then associated with the main entities using ordinary properties like 'member' or 'actor'.\\n\\nSee also \[blog post\](http://blog.schema.org/2014/06/introducing-role.html).
 *
 * @see http://schema.org/Role Documentation on Schema.org
 *
 * @ORM\Entity
 * @UniqueEntity("identifier")
 * @ApiResource(
 *     iri="http://schema.org/Role",
 *     collectionOperations={
 *          "get",
 *          "post"
 *     },
 *     itemOperations={
 *          "get",
 *          "delete"
 *     })
 */
class Role
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
     * @var string|null The identifier property represents any kind of identifier for any kind of \[\[Thing\]\], such as ISBNs, GTIN codes, UUIDs etc. Schema.org provides dedicated properties for representing many of these, either as textual strings or as URL (URI) links. See \[background notes\](/docs/datamodel.html#identifierBg) for more details.
     *
     * @ORM\Column(type="text")
     * @ApiProperty(iri="http://schema.org/identifier")
     * @Assert\NotNull
     */
    private $identifier;

    /**
     * @var string|null A role played, performed or filled by a person or organization. For example, the team of creators for a comic book might fill the roles named 'inker', 'penciller', and 'letterer'; or an athlete in a SportsTeam might play in the position named 'Quarterback'.
     *
     * @ORM\Column(type="text")
     * @ApiProperty(iri="http://schema.org/roleName")
     * @Groups({"person_single_get"})
     * @Assert\NotNull
     */
    private $roleName;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setIdentifier(?string $identifier): void
    {
        $this->identifier = $identifier;
    }

    public function getIdentifier(): ?string
    {
        return $this->identifier;
    }

    public function setRoleName(?string $roleName): void
    {
        $this->roleName = $roleName;
    }

    public function getRoleName(): ?string
    {
        return $this->roleName;
    }
}
