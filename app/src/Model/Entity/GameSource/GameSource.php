<?php

declare(strict_types=1);

namespace App\Model\Entity\GameSource;

use App\Model\Entity\Game\Game;
use App\Model\Entity\Language\Language;
use App\Model\Entity\Source\Source;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="game_buffer", uniqueConstraints={
 *     @ORM\UniqueConstraint(columns={"game_id", "language_id", "source_id", "start_date"})
 * })
 */
class GameSource
{
    /**
     * @var Id
     * @ORM\Column(type="game_source_id")
     * @ORM\Id
     */
    private $id;

    /**
     * @var Game
     * @ORM\ManyToOne(targetEntity="App\Model\Entity\Game\Game", inversedBy="buffer")
     * @ORM\JoinColumn(referencedColumnName="id", nullable=false)
     */
    private $game;

    /**
     * @var Language
     * @ORM\ManyToOne(targetEntity="App\Model\Entity\Language\Language")
     * @ORM\JoinColumn(referencedColumnName="id", nullable=false)
     */
    private $language;

    /**
     * @var Source
     * @ORM\ManyToOne(targetEntity="App\Model\Entity\Source\Source")
     * @ORM\JoinColumn(referencedColumnName="id", nullable=false)
     */
    private $source;

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
     * GameSource constructor.
     * @param Id $id
     * @param Game $game
     * @param Language $language
     * @param Source $source
     * @param \DateTimeImmutable $startDate
     * @param \DateTimeImmutable $createdAt
     * @throws \Exception
     */
    public function __construct(
        Id $id,
        Game $game,
        Language $language,
        Source $source,
        \DateTimeImmutable $startDate,
        \DateTimeImmutable $createdAt
    ) {
        $this->id = $id;
        $this->game = $game;
        $this->language = $language;
        $this->source = $source;
        $this->startDate = $startDate;
        $this->createdAt = $createdAt;

        $game->addGameSource($this);
    }

    /**
     * @return Id
     */
    public function id(): Id
    {
        return $this->id;
    }

    /**
     * @return Game
     */
    public function game(): Game
    {
        return $this->game;
    }

    /**
     * @return Language
     */
    public function language(): Language
    {
        return $this->language;
    }

    /**
     * @return Source
     */
    public function source(): Source
    {
        return $this->source;
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
