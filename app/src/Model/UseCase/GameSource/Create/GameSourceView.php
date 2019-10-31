<?php

declare(strict_types=1);

namespace App\Model\UseCase\GameSource\Create;

class GameSourceView
{
    /**
     * @var string
     */
    public $language;

    /**
     * @var string
     */
    public $sport;

    /**
     * @var string
     */
    public $league;

    /**
     * @var string
     */
    public $teamOne;

    /**
     * @var string
     */
    public $teamTwo;

    /**
     * @var string
     */
    public $startDate;

    /**
     * @var string
     */
    public $source;
}