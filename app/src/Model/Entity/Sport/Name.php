<?php

declare(strict_types=1);

namespace App\Model\Entity\Sport;

use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;
use Webmozart\Assert\Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="sport_names", uniqueConstraints={
 *     @ORM\UniqueConstraint(columns={"value"})
 * })
 */
class Name
{
    /**
     * @var string
     * @ORM\Column(type="guid")
     * @ORM\Id
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $value;

    /**
     * @var Sport
     * @ORM\ManyToOne(targetEntity="Sport", inversedBy="names")
     * @ORM\JoinColumn(referencedColumnName="id", nullable=false)
     */
    private $sport;

    /**
     * Name constructor.
     * @param string $name
     * @param Sport $sport
     * @throws \Exception
     */
    public function __construct(string $name, Sport $sport)
    {
        $this->id = Uuid::uuid4()->toString();
        $this->sport = $sport;

        Assert::notEmpty($name, 'The name of the sport cannot be empty.');
        $this->value = $name;
    }

    /**
     * @return string
     */
    public function id(): string
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function value(): string
    {
        return $this->value;
    }

    /**
     * @return Sport
     */
    public function sport(): Sport
    {
        return $this->sport;
    }
}
