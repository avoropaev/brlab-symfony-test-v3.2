<?php

declare(strict_types=1);

namespace App\Tests\Builder;

use App\Model\Entity\Game\Game;
use App\Model\Entity\Language\Language;
use App\Model\Entity\GameSource\Id;
use App\Model\Entity\GameSource\GameSource;
use App\Model\Entity\Source\Source;

class GameSourceBuilder
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
     * GameSourceBuilder constructor.
     */
    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
        $this->startDate = new \DateTimeImmutable();
    }

    /**
     * @param Game $game
     * @param Language $language
     * @param Source $source
     * @return GameSource
     * @throws \Exception
     */
    public function build(Game $game, Language $language, Source $source): GameSource
    {
        return new GameSource(
            Id::next(),
            $game,
            $language,
            $source,
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