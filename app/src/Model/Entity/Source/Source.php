<?php

declare(strict_types=1);

namespace App\Model\Entity\Source;

use Doctrine\ORM\Mapping as ORM;
use Webmozart\Assert\Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="sources", uniqueConstraints={
 *     @ORM\UniqueConstraint(columns={"url"})
 * })
 */
class Source
{
    /**
     * @var Id
     * @ORM\Column(type="source_id")
     * @ORM\Id
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $name;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $url;

    /**
     * @var \DateTimeImmutable
     * @ORM\Column(type="datetime_immutable")
     */
    private $createdAt;

    /**
     * Source constructor.
     * @param Id $id
     * @param string $name
     * @param string $url
     * @param \DateTimeImmutable $createdAt
     */
    public function __construct(Id $id, string $name, string $url, \DateTimeImmutable $createdAt)
    {
        $this->id = $id;
        $this->createdAt = $createdAt;

        Assert::notEmpty($name, 'Name cannot be empty.');
        $this->name = $name;

        Assert::notEmpty($url, 'Url cannot be empty.');
        $this->url = $url;
    }

    /**
     * @return Id
     */
    public function id(): Id
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function name(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function url(): string
    {
        return $this->url;
    }

    /**
     * @return \DateTimeImmutable
     */
    public function createdAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }
}
