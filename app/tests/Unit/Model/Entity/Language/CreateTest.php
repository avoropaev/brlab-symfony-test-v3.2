<?php

declare(strict_types=1);

namespace App\Tests\Unit\Model\Entity\Language;

use App\Model\Entity\Language\Id;
use App\Model\Entity\Language\Language;
use App\Model\Entity\Language\Name;
use App\Tests\Builder\LanguageBuilder;
use PHPUnit\Framework\TestCase;

class CreateTest extends TestCase
{
    /**
     * @throws \Exception
     */
    public function testSuccess(): void
    {
        $language = new Language(
            $id = Id::next(),
            $displayName = 'Language',
            $createdAt = new \DateTimeImmutable(),
            ['Language', 'язык']
        );

        self::assertEquals($id, $language->id());
        self::assertEquals($displayName, $language->displayName());
        self::assertEquals($createdAt, $language->createdAt());

        foreach ($language->names() as $name) {
            self::assertInstanceOf(Name::class, $name);
        }
    }

    /**
     * @throws \Exception
     */
    public function testEmptyDisplayName(): void
    {
        $this->expectExceptionMessage('Display name cannot be empty.');
        (new LanguageBuilder())->withDisplayName('')->build();
    }

    /**
     * @throws \Exception
     */
    public function testNotStringNames(): void
    {
        $this->expectExceptionMessage('Names must be strings.');
        (new LanguageBuilder())->withNames([1, 2])->build();
    }

    /**
     * @throws \Exception
     */
    public function testNotUniqueNames(): void
    {
        $this->expectExceptionMessage('Names must be unique.');
        (new LanguageBuilder())->withNames(['test', 'test'])->build();
    }

    /**
     * @throws \Exception
     */
    public function testEmptyNames(): void
    {
        $this->expectExceptionMessage('The name of the language cannot be empty.');
        (new LanguageBuilder())->withNames(['', 'test'])->build();
    }
}
