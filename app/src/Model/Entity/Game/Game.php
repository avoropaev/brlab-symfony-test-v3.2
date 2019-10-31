<?php

declare(strict_types=1);

namespace App\Model\Entity\Game;

use App\Model\Entity\GameSource\GameSource;
use App\Model\Entity\League\League;
use App\Model\Entity\Team\Team;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Webmozart\Assert\Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="games", uniqueConstraints={
 *     @ORM\UniqueConstraint(columns={"league_id", "team_one_id", "team_two_id", "start_date"})
 * })
 */
class Game
{
    /**
     * @var Id
     * @ORM\Column(type="game_id")
     * @ORM\Id
     */
    private $id;

    /**
     * @var League
     * @ORM\ManyToOne(targetEntity="App\Model\Entity\League\League")
     * @ORM\JoinColumn(referencedColumnName="id", nullable=false)
     */
    private $league;

    /**
     * @var Team
     * @ORM\ManyToOne(targetEntity="App\Model\Entity\Team\Team")
     * @ORM\JoinColumn(referencedColumnName="id", nullable=false)
     */
    private $teamOne;

    /**
     * @var Team
     * @ORM\ManyToOne(targetEntity="App\Model\Entity\Team\Team")
     * @ORM\JoinColumn(referencedColumnName="id", nullable=false)
     */
    private $teamTwo;

    /**
     * @var GameSource[]|ArrayCollection
     * @ORM\OneToMany(targetEntity="App\Model\Entity\GameSource\GameSource", mappedBy="game", orphanRemoval=true)
     */
    private $buffer;

    /**
     * @var \DateTimeImmutable
     * @ORM\Column(type="datetime_immutable")
     */
    private $startDate;

    /**
     * @var \DateTimeImmutable
     * @ORM\Column(type="datetime_immutable")
     */
    private $createdAt;

    /**
     * Game constructor.
     * @param Id $id
     * @param League $league
     * @param Team $teamOne
     * @param Team $teamTwo
     * @param \DateTimeImmutable $startDate
     * @param \DateTimeImmutable $createdAt
     */
    public function __construct(
        Id $id,
        League $league,
        Team $teamOne,
        Team $teamTwo,
        \DateTimeImmutable $startDate,
        \DateTimeImmutable $createdAt
    ) {
        $this->id = $id;
        $this->league = $league;
        $this->startDate = $startDate;
        $this->createdAt = $createdAt;
        $this->buffer = new ArrayCollection();

        Assert::notEq($teamOne, $teamTwo, 'The same team cannot participate in the game.');
        Assert::eq($league->sport(), $teamOne->sport(), 'Team and league must be from the same sport.');
        Assert::eq($league->sport(), $teamTwo->sport(), 'Team and league must be from the same sport.');
        $this->teamOne = $teamOne;
        $this->teamTwo = $teamTwo;
    }

    /**
     * @param GameSource $gameSource
     * @throws \Exception
     */
    public function addGameSource(GameSource $gameSource): void
    {
        if (!$this->buffer->contains($gameSource)) {
            $this->buffer->add($gameSource);
        }

        $startDates = array_map(static function($value) {
            /** @var GameSource $value */
            return $value->startDate()->format(DATE_W3C);
        }, $this->buffer->toArray());

        $startDates = array_count_values($startDates);
        $startDate = array_search(max($startDates), $startDates);

        $this->startDate = (new \DateTimeImmutable($startDate));
    }

    /**
     * @return Id
     */
    public function id(): Id
    {
        return $this->id;
    }

    /**
     * @return League
     */
    public function league(): League
    {
        return $this->league;
    }

    /**
     * @return Team
     */
    public function teamOne(): Team
    {
        return $this->teamOne;
    }

    /**
     * @return Team
     */
    public function teamTwo(): Team
    {
        return $this->teamTwo;
    }

    /**
     * @return GameSource[]|array
     */
    public function buffer(): array
    {
        return $this->buffer->toArray();
    }

    /**
     * @return \DateTimeImmutable
     */
    public function startDate(): \DateTimeImmutable
    {
        return $this->startDate;
    }

    /**
     * @return \DateTimeImmutable
     */
    public function createdAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }
}
