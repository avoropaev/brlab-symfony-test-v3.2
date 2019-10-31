<?php

declare(strict_types=1);

namespace App\Tests\Unit\Model\Entity\Sport;

use App\Model\Entity\Sport\Id;
use App\Model\Entity\Sport\Name;
use App\Model\Entity\Sport\Sport;
use App\Tests\Builder\SportBuilder;
use PHPUnit\Framework\TestCase;

class CreateTest extends TestCase
{
    /**
     * @throws \Exception
     */
    public function testSuccess(): void
    {
        $sport = new Sport(
            $id = Id::next(),
            $displayName = 'Sport',
            $interval = 3120,
            $createdAt = new \DateTimeImmutable(),
            ['sport', 'спорт']
        );

        self::assertEquals($id, $sport->id());
        self::assertEquals($displayName, $sport->displayName());
        self::assertEquals($interval, $sport->minIntervalBetweenGames());
        self::assertEquals($createdAt, $sport->createdAt());

        foreach ($sport->names() as $name) {
            self::assertInstanceOf(Name::class, $name);
        }
    }

    /**
     * @throws \Exception
     */
    public function testZeroInterval(): void
    {
        $this->expectExceptionMessage('The minimum interval between games must be greater than 0.');
        (new SportBuilder())->withMinIntervalBetweenGames(0)->build();
    }

    /**
     * @throws \Exception
     */
    public function testEmptyDisplayName(): void
    {
        $this->expectExceptionMessage('Display name cannot be empty.');
        (new SportBuilder())->withDisplayName('')->build();
    }

    /**
     * @throws \Exception
     */
    public function testNotStringNames(): void
    {
        $this->expectExceptionMessage('Names must be strings.');
        (new SportBuilder())->withNames([1, 2])->build();
    }

    /**
     * @throws \Exception
     */
    public function testNotUniqueNames(): void
    {
        $this->expectExceptionMessage('Names must be unique.');
        (new SportBuilder())->withNames(['test', 'test'])->build();
    }

    /**
     * @throws \Exception
     */
    public function testEmptyNames(): void
    {
        $this->expectExceptionMessage('The name of the sport cannot be empty.');
        (new SportBuilder())->withNames(['', 'test'])->build();
    }
}
