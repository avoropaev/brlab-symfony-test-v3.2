<?php

declare(strict_types=1);

namespace App\Model\Entity\Language;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Webmozart\Assert\Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="languages", uniqueConstraints={
 *     @ORM\UniqueConstraint(columns={"display_name"})
 * })
 */
class Language
{
    /**
     * @var Id
     * @ORM\Column(type="language_id")
     * @ORM\Id
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $displayName;

    /**
     * @var Name[]|ArrayCollection
     * @ORM\OneToMany(targetEntity="Name", mappedBy="language", orphanRemoval=true, cascade={"all"})
     */
    private $names;

    /**
     * @var \DateTimeImmutable
     * @ORM\Column(type="datetime_immutable")
     */
    private $createdAt;

    /**
     * Language constructor.
     * @param Id $id
     * @param string $displayName
     * @param \DateTimeImmutable $createdAt
     * @param array $names
     */
    public function __construct(Id $id, string $displayName, \DateTimeImmutable $createdAt, array $names = [])
    {
        $this->id = $id;
        $this->createdAt = $createdAt;

        Assert::notEmpty($displayName, 'Display name cannot be empty.');
        $this->displayName = $displayName;

        Assert::allString($names, 'Names must be strings.');
        Assert::uniqueValues($names, 'Names must be unique.');

        $names = array_map(function($name) {
           return new Name($name, $this);
        }, $names);

        $this->names = new ArrayCollection($names);
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
    public function displayName(): string
    {
        return $this->displayName;
    }

    /**
     * @return array
     */
    public function names(): array
    {
        return $this->names->toArray();
    }

    /**
     * @return \DateTimeImmutable
     */
    public function createdAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }
}
