<?php

declare(strict_types=1);

namespace App\Tests\Unit\Model\Entity\Team;

use App\Model\Entity\Team\Id;
use App\Model\Entity\Team\Name;
use App\Model\Entity\Team\Team;
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

        $team = new Team(
            $id = Id::next(),
            $displayName = 'Team',
            $sport,
            $createdAt = new \DateTimeImmutable(),
            ['team', 'команда']
        );

        self::assertEquals($id, $team->id());
        self::assertEquals($displayName, $team->displayName());
        self::assertEquals($sport, $team->sport());
        self::assertEquals($createdAt, $team->createdAt());

        foreach ($team->names() as $name) {
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
        (new TeamBuilder())->withDisplayName('')->build($sport);
    }

    /**
     * @throws \Exception
     */
    public function testNotStringNames(): void
    {
        $sport = (new SportBuilder())->build();

        $this->expectExceptionMessage('Names must be strings.');
        (new TeamBuilder())->withNames([1, 2])->build($sport);
    }

    /**
     * @throws \Exception
     */
    public function testNotUniqueNames(): void
    {
        $sport = (new SportBuilder())->build();

        $this->expectExceptionMessage('Names must be unique.');
        (new TeamBuilder())->withNames(['test', 'test'])->build($sport);
    }

    /**
     * @throws \Exception
     */
    public function testEmptyNames(): void
    {
        $sport = (new SportBuilder())->build();

        $this->expectExceptionMessage('The name of the team cannot be empty.');
        (new TeamBuilder())->withNames(['', 'test'])->build($sport);
    }
}
