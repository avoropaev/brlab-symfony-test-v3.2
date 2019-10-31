<?php

declare(strict_types=1);

namespace App\Model\UseCase\GameSource\Create;

use Symfony\Component\Validator\Constraints as Assert;

class Command
{
    /**
     * @var GameSourceView[]|array
     * @Assert\Type("array")
     * @Assert\NotNull()
     * @Assert\NotBlank()
     * @Assert\All(
     *     @Assert\Type("object")
     * )
     * @Assert\Valid()
     */
    private $games;

    /**
     * Command constructor.
     */
    public function __construct()
    {
        $this->games = [];
    }

    /**
     * @return GameSourceView[]|array
     */
    public function getGames(): array
    {
        return $this->games;
    }

    /**
     * @param GameSourceView $game
     */
    public function addGame(GameSourceView $game): void
    {
        if (!in_array($game, $this->games, true)) {
            $this->games[] = $game;
        }
    }

    /**
     * @param GameSourceView $game
     */
    public function removeGame(GameSourceView $game): void
    {
        if (in_array($game, $this->games, true)) {
            $key = array_search($game, $this->games, true);

            if ($key === false) {
                return;
            }

            unset($this->games[$key]);
        }
    }
}