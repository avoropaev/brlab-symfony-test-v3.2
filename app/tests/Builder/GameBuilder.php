<?php

declare(strict_types=1);

namespace App\Tests\Builder;

use App\Model\Entity\League\League;
use App\Model\Entity\Game\Id;
use App\Model\Entity\Game\Game;
use App\Model\Entity\Team\Team;

class GameBuilder
{
    /**
     * @var \DateTimeImmutable
     */
    private $startDate;
    /**
     * @var \DateTimeImmutable
     */
    private $createdAt;

    /**
     * GameBuilder constructor.
     */
    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
        $this->startDate = new \DateTimeImmutable();
    }

    /**
     * @param League $league
     * @param Team $teamOne
     * @param Team $teamTwo
     * @return Game
     * @throws \Exception
     */
    public function build(League $league, Team $teamOne, Team $teamTwo): Game
    {
        return new Game(
            Id::next(),
            $league,
            $teamOne,
            $teamTwo,
            $this->startDate,
            $this->createdAt
        );
    }

    /**
     * @param \DateTimeImmutable $startDate
     * @return $this
     */
    public function withStartDate(\DateTimeImmutable $startDate): self
    {
        $clone = clone $this;
        $clone->startDate = $startDate;

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
}