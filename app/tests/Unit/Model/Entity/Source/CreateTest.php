<?php

declare(strict_types=1);

namespace App\Tests\Unit\Model\Entity\Source;

use App\Model\Entity\Source\Id;
use App\Model\Entity\Source\Source;
use App\Tests\Builder\SourceBuilder;
use PHPUnit\Framework\TestCase;

class CreateTest extends TestCase
{
    /**
     * @throws \Exception
     */
    public function testSuccess(): void
    {
        $source = new Source(
            $id = Id::next(),
            $name = 'Source',
            $url = 'source.com',
            $createdAt = new \DateTimeImmutable()
        );

        self::assertEquals($id, $source->id());
        self::assertEquals($name, $source->name());
        self::assertEquals($url, $source->url());
        self::assertEquals($createdAt, $source->createdAt());
    }

    /**
     * @throws \Exception
     */
    public function testEmptyName(): void
    {
        $this->expectExceptionMessage('Name cannot be empty.');
        (new SourceBuilder())->withName('')->build();
    }

    /**
     * @throws \Exception
     */
    public function testEmptyUrl(): void
    {
        $this->expectExceptionMessage('Url cannot be empty.');
        (new SourceBuilder())->withUrl('')->build();
    }
}
