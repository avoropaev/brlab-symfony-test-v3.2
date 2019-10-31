<?php

declare(strict_types=1);

namespace App\Tests\Unit\Model\Entity\Game;

use App\Tests\Builder\GameSourceBuilder;
use App\Tests\Builder\LanguageBuilder;
use App\Tests\Builder\LeagueBuilder;
use App\Tests\Builder\SourceBuilder;
use App\Tests\Builder\SportBuilder;
use App\Tests\Builder\GameBuilder;
use App\Tests\Builder\TeamBuilder;
use PHPUnit\Framework\TestCase;

class AddGameSourceTest extends TestCase
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

        $game = (new GameBuilder())->build($league, $teamOne, $teamTwo);
        $language = (new LanguageBuilder())->build();
        $source = (new SourceBuilder())->build();
        $gameSource = (new GameSourceBuilder())->build($game, $language, $source);

        $game->addGameSource($gameSource);
        self::assertTrue(in_array($gameSource, $game->buffer(), true));

        $startDate = new \DateTimeImmutable('-10 hours');
        self::assertNotEquals($game->startDate(), $startDate);

        $gameSourceOne = (new GameSourceBuilder())
            ->withStartDate($startDate)
            ->build($game, $language, $source);

        $gameSourceTwo = (new GameSourceBuilder())
            ->withStartDate($startDate)
            ->build($game, $language, $source);

        $game->addGameSource($gameSourceOne);
        $game->addGameSource($gameSourceTwo);

        self::assertEquals($game->startDate()->getTimestamp(), $startDate->getTimestamp());
    }
}
