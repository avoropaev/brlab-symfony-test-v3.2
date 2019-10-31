<?php

declare(strict_types=1);

namespace App\Model\Entity\Team;

use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;
use Webmozart\Assert\Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="team_names")
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
     * @var Team
     * @ORM\ManyToOne(targetEntity="Team", inversedBy="names")
     * @ORM\JoinColumn(referencedColumnName="id", nullable=false)
     */
    private $team;

    /**
     * Name constructor.
     * @param string $name
     * @param Team $team
     * @throws \Exception
     */
    public function __construct(string $name, Team $team)
    {
        $this->id = Uuid::uuid4()->toString();
        $this->team = $team;

        Assert::notEmpty($name, 'The name of the team cannot be empty.');
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
     * @return Team
     */
    public function team(): Team
    {
        return $this->team;
    }
}
