<?php

declare(strict_types=1);

namespace App\Tests\Builder;

use App\Model\Entity\Sport\Sport;
use App\Model\Entity\League\Id;
use App\Model\Entity\League\League;

class LeagueBuilder
{
    /**
     * @var string
     */
    private $displayName;

    /**
     * @var \DateTimeImmutable
     */
    private $createdAt;

    /**
     * @var string[]|array
     */
    private $names;

    /**
     * LeagueBuilder constructor.
     */
    public function __construct()
    {
        $this->displayName = 'League';
        $this->createdAt = new \DateTimeImmutable();
        $this->names = [
            'League',
            'лига'
        ];
    }

    /**
     * @param Sport $sport
     * @return League
     * @throws \Exception
     */
    public function build(Sport $sport): League
    {
        return new League(
            Id::next(),
            $this->displayName,
            $sport,
            $this->createdAt,
            $this->names
        );
    }

    /**
     * @param string $displayName
     * @return $this
     */
    public function withDisplayName(string $displayName): self
    {
        $clone = clone $this;
        $clone->displayName = $displayName;

        return $clone;
    }

    /**
     * @param \DateTimeImmutable $createdAt
     * @return $this
     */
    public function withCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $clone = clone $this;
        $clone->createdAt = $createdAt;

        return $clone;
    }

    /**
     * @param string[]|array $names
     * @return $this
     */
    public function withNames(array $names): self
    {
        $clone = clone $this;
        $clone->names = $names;

        return $clone;
    }
}