<?php

declare(strict_types=1);

namespace App\Model\Entity\Team;

use App\Model\Entity\Sport\Sport;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Webmozart\Assert\Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="teams", uniqueConstraints={
 *     @ORM\UniqueConstraint(columns={"sport_id", "display_name"})
 * })
 */
class Team
{
    /**
     * @var Id
     * @ORM\Column(type="team_id")
     * @ORM\Id
     */
    private $id;

    /**
     * @var Sport
     * @ORM\ManyToOne(targetEntity="App\Model\Entity\Sport\Sport")
     * @ORM\JoinColumn(referencedColumnName="id", nullable=false)
     */
    private $sport;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $displayName;

    /**
     * @var string[]|ArrayCollection
     * @ORM\OneToMany(targetEntity="Name", mappedBy="team", orphanRemoval=true, cascade={"all"})
     */
    private $names;

    /**
     * @var \DateTimeImmutable
     * @ORM\Column(type="datetime_immutable")
     */
    private $createdAt;

    /**
     * Team constructor.
     * @param Id $id
     * @param string $displayName
     * @param Sport $sport
     * @param \DateTimeImmutable $createdAt
     * @param array $names
     */
    public function __construct(
        Id $id,
        string $displayName,
        Sport $sport,
        \DateTimeImmutable $createdAt,
        array $names = []
    ) {
        $this->id = $id;
        $this->sport = $sport;
        $this->createdAt = $createdAt;

        Assert::notEmpty($displayName, 'Display name cannot be empty.');
        $this->displayName = $displayName;

        Assert::allString($names, 'Names must be strings.');
        Assert::uniqueValues($names, 'Names must be unique.');

        $names = array_map(function($name) {
            return new Name($name, $this);
        }, $names);

        $this->names = new ArrayCollection($names);
    }

    /**
     * @return Id
     */
    public function id(): Id
    {
        return $this->id;
    }

    /**
     * @return Sport
     */
    public function sport(): Sport
    {
        return $this->sport;
    }

    /**
     * @return string
     */
    public function displayName(): string
    {
        return $this->displayName;
    }

    /**
     * @return array
     */
    public function names(): array
    {
        return $this->names->toArray();
    }

    /**
     * @return \DateTimeImmutable
     */
    public function createdAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }
}
