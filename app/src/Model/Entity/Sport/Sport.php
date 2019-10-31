<?php

declare(strict_types=1);

namespace App\Model\Entity\Sport;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Webmozart\Assert\Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="sports", uniqueConstraints={
 *     @ORM\UniqueConstraint(columns={"display_name"})
 * })
 */
class Sport
{
    /**
     * @var Id
     * @ORM\Column(type="sport_id")
     * @ORM\Id
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $displayName;

    /**
     * @var string[]|ArrayCollection
     * @ORM\OneToMany(targetEntity="Name", mappedBy="sport", orphanRemoval=true, cascade={"all"})
     */
    private $names;

    /**
     * Minimum interval between games in minutes
     * Example:
     *      football: 3120 minutes = 52 hours = +/- 26 hours
     *
     * @var int
     * @ORM\Column(type="integer")
     */
    private $minIntervalBetweenGames;

    /**
     * @var \DateTimeImmutable
     * @ORM\Column(type="datetime_immutable")
     */
    private $createdAt;

    /**
     * Sport constructor.
     * @param Id $id
     * @param string $displayName
     * @param int $minIntervalBetweenGames
     * @param \DateTimeImmutable $createdAt
     * @param array $names
     */
    public function __construct(
        Id $id,
        string $displayName,
        int $minIntervalBetweenGames,
        \DateTimeImmutable $createdAt,
        array $names = []
    ) {
        $this->id = $id;
        $this->createdAt = $createdAt;

        Assert::greaterThan($minIntervalBetweenGames, 0, 'The minimum interval between games must be greater than 0.');
        $this->minIntervalBetweenGames = $minIntervalBetweenGames;

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
     * @return string
     */
    public function displayName(): string
    {
        return $this->displayName;
    }

    /**
     * @return int
     */
    public function minIntervalBetweenGames(): int
    {
        return $this->minIntervalBetweenGames;
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
