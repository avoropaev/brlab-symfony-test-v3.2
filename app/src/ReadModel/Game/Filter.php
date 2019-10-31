<?php

declare(strict_types=1);

namespace App\ReadModel\Game;

use Symfony\Component\Validator\Constraints as Assert;

class Filter
{
    /**
     * @var string
     */
    public $source;

    /**
     * @var string
     * @Assert\DateTime()
     */
    public $startDateStart;

    /**
     * @var string
     * @Assert\DateTime()
     */
    public $startDateEnd;
}
