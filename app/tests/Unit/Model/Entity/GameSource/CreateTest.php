<?php

declare(strict_types=1);

namespace App\Tests\Unit\Model\Entity\GameSource;

use App\Model\Entity\GameSource\Id;
use App\Model\Entity\GameSource\GameSource;
use App\Tests\Builder\GameBuilder;
use App\Tests\Builder\LanguageBuilder;
use App\Tests\Builder\LeagueBuilder;
use App\Tests\Builder\SourceBuilder;
use App\Tests\Builder\SportBuilder;
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

        $game = (new GameBuilder())->build($league, $teamOne, $teamTwo);
        $language = (new LanguageBuilder())->build();
        $source = (new SourceBuilder())->build();

        $gameSource = new GameSource(
            $id = Id::next(),
            $game,
            $language,
            $source,
            $startDate = new \DateTimeImmutable(),
            $createdAt = new \DateTimeImmutable()
        );

        self::assertEquals($id, $gameSource->id());
        self::assertEquals($game, $gameSource->game());
        self::assertEquals($language, $gameSource->language());
        self::assertEquals($source, $gameSource->source());
        self::assertEquals($startDate, $gameSource->startDate());
        self::assertEquals($createdAt, $gameSource->createdAt());
    }
}
