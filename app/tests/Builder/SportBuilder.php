<?php

declare(strict_types=1);

namespace App\Tests\Builder;

use App\Model\Entity\Sport\Id;
use App\Model\Entity\Sport\Sport;

class SportBuilder
{
    /**
     * @var string
     */
    private $displayName;

    /**
     * @var int
     */
    private $minIntervalBetweenGames;

    /**
     * @var \DateTimeImmutable
     */
    private $createdAt;

    /**
     * @var string[]|array
     */
    private $names;

    /**
     * SportBuilder constructor.
     */
    public function __construct()
    {
        $this->displayName = 'Sport';
        $this->minIntervalBetweenGames = 3120;
        $this->createdAt = new \DateTimeImmutable();
        $this->names = [
            'sport',
            'спорт'
        ];
    }

    /**
     * @return Sport
     * @throws \Exception
     */
    public function build(): Sport
    {
        return new Sport(
            Id::next(),
            $this->displayName,
            $this->minIntervalBetweenGames,
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
     * @param int $interval
     * @return $this
     */
    public function withMinIntervalBetweenGames(int $interval): self
    {
        $clone = clone $this;
        $clone->minIntervalBetweenGames = $interval;

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