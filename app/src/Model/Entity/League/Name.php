<?php

declare(strict_types=1);

namespace App\Model\Entity\League;

use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;
use Webmozart\Assert\Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="league_names")
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
     * @var League
     * @ORM\ManyToOne(targetEntity="League", inversedBy="names")
     * @ORM\JoinColumn(referencedColumnName="id", nullable=false)
     */
    private $league;

    /**
     * Name constructor.
     * @param string $name
     * @param League $league
     * @throws \Exception
     */
    public function __construct(string $name, League $league)
    {
        $this->id = Uuid::uuid4()->toString();
        $this->league = $league;

        Assert::notEmpty($name, 'The name of the league cannot be empty.');
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
     * @return League
     */
    public function league(): League
    {
        return $this->league;
    }
}
