<?php

declare(strict_types=1);

namespace App\Tests\Builder;

use App\Model\Entity\Language\Id;
use App\Model\Entity\Language\Language;

class LanguageBuilder
{
    /**
     * @var string
     */
    private $displayName;

    /**
     * @var \DateTimeImmutable
     */
    private $createdAt;

    /**
     * @var string[]|array
     */
    private $names;

    /**
     * LanguageBuilder constructor.
     */
    public function __construct()
    {
        $this->displayName = 'Language';
        $this->createdAt = new \DateTimeImmutable();
        $this->names = [
            'Language',
            'язык'
        ];
    }

    /**
     * @return Language
     * @throws \Exception
     */
    public function build(): Language
    {
        return new Language(
            Id::next(),
            $this->displayName,
            $this->createdAt,
            $this->names
        );
    }

    /**
     * @param string $displayName
     * @return $this
     */
    public function withDisplayName(string $displayName): self
    {
        $clone = clone $this;
        $clone->displayName = $displayName;

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

    /**
     * @param string[]|array $names
     * @return $this
     */
    public function withNames(array $names): self
    {
        $clone = clone $this;
        $clone->names = $names;

        return $clone;
    }
}