<?php

declare(strict_types=1);

namespace App\Tests\Unit\Model\Entity\Game;

use App\Model\Entity\Game\Id;
use App\Model\Entity\Game\Game;
use App\Tests\Builder\LeagueBuilder;
use App\Tests\Builder\SportBuilder;
use App\Tests\Builder\GameBuilder;
use App\Tests\Builder\TeamBuilder;
use PHPUnit\Framework\TestCase;

class CreateTest extends TestCase
{
    /**
     * @throws \Exception
     */
    public function testSuccess(): void
    {
        $sport = (new SportBuilder())->build();
        $league = (new LeagueBuilder())->build($sport);
        $teamOne = (new TeamBuilder())->build($sport);
        $teamTwo = (new TeamBuilder())->build($sport);

        $game = new Game(
            $id = Id::next(),
            $league,
            $teamOne,
            $teamTwo,
            $startDate = new \DateTimeImmutable(),
            $createdAt = new \DateTimeImmutable()
        );

        self::assertEquals($id, $game->id());
        self::assertEquals($league, $game->league());
        self::assertEquals($teamOne, $game->teamOne());
        self::assertEquals($teamTwo, $game->teamTwo());
        self::assertEquals($startDate, $game->startDate());
        self::assertEquals($createdAt, $game->createdAt());
        self::assertEmpty($game->buffer());
    }

    /**
     * @throws \Exception
     */
    public function testSameTeams(): void
    {
        $sport = (new SportBuilder())->build();
        $league = (new LeagueBuilder())->build($sport);
        $team = (new TeamBuilder())->build($sport);

        $this->expectExceptionMessage('The same team cannot participate in the game.');
        (new GameBuilder())->build($league, $team, $team);
    }

    /**
     * @throws \Exception
     */
    public function testDifferentSportTeamOne(): void
    {
        $sportOne = (new SportBuilder())->build();
        $sportTwo = (new SportBuilder())->build();
        $league = (new LeagueBuilder())->build($sportOne);
        $teamOne = (new TeamBuilder())->build($sportTwo);
        $teamTwo = (new TeamBuilder())->build($sportOne);

        $this->expectExceptionMessage('Team and league must be from the same sport.');
        (new GameBuilder())->build($league, $teamOne, $teamTwo);
    }

    /**
     * @throws \Exception
     */
    public function testDifferentSportTeamTwo(): void
    {
        $sportOne = (new SportBuilder())->build();
        $sportTwo = (new SportBuilder())->build();
        $league = (new LeagueBuilder())->build($sportOne);
        $teamOne = (new TeamBuilder())->build($sportOne);
        $teamTwo = (new TeamBuilder())->build($sportTwo);

        $this->expectExceptionMessage('Team and league must be from the same sport.');
        (new GameBuilder())->build($league, $teamOne, $teamTwo);
    }
}
