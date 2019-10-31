<?php

declare(strict_types=1);

namespace App\Tests\Builder;

use App\Model\Entity\Source\Source;
use App\Model\Entity\Source\Id;

class SourceBuilder
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $url;

    /**
     * @var \DateTimeImmutable
     */
    private $createdAt;

    /**
     * SourceBuilder constructor.
     */
    public function __construct()
    {
        $this->name = 'Source';
        $this->url = 'source.com';
        $this->createdAt = new \DateTimeImmutable();
    }

    /**
     * @return Source
     * @throws \Exception
     */
    public function build(): Source
    {
        return new Source(
            Id::next(),
            $this->name,
            $this->url,
            $this->createdAt
        );
    }

    /**
     * @param string $name
     * @return $this
     */
    public function withName(string $name): self
    {
        $clone = clone $this;
        $clone->name = $name;

        return $clone;
    }

    /**
     * @param string $url
     * @return $this
     */
    public function withUrl(string $url): self
    {
        $clone = clone $this;
        $clone->url = $url;

        return $clone;
    }

    /**
     * @param \DateTimeImmutable $createdAt
     * @return $this
     */
    public function withCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $clone = clone $this;
        $clone->createdAt = $createdAt;

        return $clone;
    }
}