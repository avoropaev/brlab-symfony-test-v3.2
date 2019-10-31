<?php

declare(strict_types=1);

namespace App\Model\Entity\Language;

use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;
use Webmozart\Assert\Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="language_names", uniqueConstraints={
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
     * @var Language
     * @ORM\ManyToOne(targetEntity="Language", inversedBy="names")
     * @ORM\JoinColumn(referencedColumnName="id", nullable=false)
     */
    private $language;

    /**
     * Name constructor.
     * @param string $name
     * @param Language $language
     * @throws \Exception
     */
    public function __construct(string $name, Language $language)
    {
        $this->id = Uuid::uuid4()->toString();
        $this->language = $language;

        Assert::notEmpty($name, 'The name of the language cannot be empty.');
        $this->value = mb_strtolower($name);
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
     * @return Language
     */
    public function language(): Language
    {
        return $this->language;
    }
}
