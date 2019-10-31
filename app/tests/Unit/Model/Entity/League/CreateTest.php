<?php

declare(strict_types=1);

namespace App\Tests\Unit\Model\Entity\League;

use App\Model\Entity\League\Id;
use App\Model\Entity\League\Name;
use App\Model\Entity\League\League;
use App\Tests\Builder\SportBuilder;
use App\Tests\Builder\LeagueBuilder;
use PHPUnit\Framework\TestCase;

class CreateTest extends TestCase
{
    /**
     * @throws \Exception
     */
    public function testSuccess(): void
    {
        $sport = (new SportBuilder())->build();

        $league = new League(
            $id = Id::next(),
            $displayName = 'League',
            $sport,
            $createdAt = new \DateTimeImmutable(),
            ['league', 'лига']
        );

        self::assertEquals($id, $league->id());
        self::assertEquals($displayName, $league->displayName());
        self::assertEquals($sport, $league->sport());
        self::assertEquals($createdAt, $league->createdAt());

        foreach ($league->names() as $name) {
            self::assertInstanceOf(Name::class, $name);
        }
    }

    /**
     * @throws \Exception
     */
    public function testEmptyDisplayName(): void
    {
        $sport = (new SportBuilder())->build();

        $this->expectExceptionMessage('Display name cannot be empty.');
        (new LeagueBuilder())->withDisplayName('')->build($sport);
    }

    /**
     * @throws \Exception
     */
    public function testNotStringNames(): void
    {
        $sport = (new SportBuilder())->build();

        $this->expectExceptionMessage('Names must be strings.');
        (new LeagueBuilder())->withNames([1, 2])->build($sport);
    }

    /**
     * @throws \Exception
     */
    public function testNotUniqueNames(): void
    {
        $sport = (new SportBuilder())->build();

        $this->expectExceptionMessage('Names must be unique.');
        (new LeagueBuilder())->withNames(['test', 'test'])->build($sport);
    }

    /**
     * @throws \Exception
     */
    public function testEmptyNames(): void
    {
        $sport = (new SportBuilder())->build();

        $this->expectExceptionMessage('The name of the league cannot be empty.');
        (new LeagueBuilder())->withNames(['', 'test'])->build($sport);
    }
}
