<?php

declare(strict_types=1);

namespace App\Model\UseCase\GameSource\Create;

use App\Model\Entity\Game\Game;
use App\Model\Entity\Game\GameRepository;
use App\Model\Entity\GameSource\GameSource;
use App\Model\Entity\GameSource\GameSourceRepository;
use App\Model\Entity\Language\LanguageRepository;
use App\Model\Entity\League\LeagueRepository;
use App\Model\Entity\Source\SourceRepository;
use App\Model\Entity\Sport\SportRepository;
use App\Model\Entity\Team\TeamRepository;
use App\Model\Flusher;
use App\Model\Entity\GameSource\Id as GameSourceId;
use App\Model\Entity\Game\Id as GameId;

class Handler
{
    /**
     * @var GameRepository
     */
    private $games;

    /**
     * @var Flusher
     */
    private $flusher;

    /**
     * @var LanguageRepository
     */
    private $languages;

    /**
     * @var SourceRepository
     */
    private $sources;

    /**
     * @var LeagueRepository
     */
    private $leagues;

    /**
     * @var TeamRepository
     */
    private $teams;

    /**
     * @var SportRepository
     */
    private $sports;

    /**
     * @var GameSourceRepository
     */
    private $gameSources;

    /**
     * Handler constructor.
     * @param GameRepository $games
     * @param LanguageRepository $languages
     * @param SportRepository $sports
     * @param SourceRepository $sources
     * @param LeagueRepository $leagues
     * @param TeamRepository $teams
     * @param GameSourceRepository $gameSources
     * @param Flusher $flusher
     */
    public function __construct(
        GameRepository $games,
        LanguageRepository $languages,
        SportRepository $sports,
        SourceRepository $sources,
        LeagueRepository $leagues,
        TeamRepository $teams,
        GameSourceRepository $gameSources,
        Flusher $flusher
    ) {
        $this->games = $games;
        $this->flusher = $flusher;
        $this->languages = $languages;
        $this->sources = $sources;
        $this->leagues = $leagues;
        $this->teams = $teams;
        $this->sports = $sports;
        $this->gameSources = $gameSources;
    }

    /**
     * @param Command $command
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @throws \Exception
     */
    public function handle(Command $command): void
    {
        foreach ($command->getGames() as $gameView) {
            if (null === $language = $this->languages->findByName($gameView->language)) {
                /** Here may be saving logs or errors for output to the client. */
                continue;
            }

            if (null === $sport = $this->sports->findByName($gameView->sport)) {
                /** Here may be saving logs or errors for output to the client. */
                continue;
            }

            if (null === $league = $this->leagues->findByNameAndSport($gameView->league, $sport->id())) {
                /** Here may be saving logs or errors for output to the client. */
                continue;
            }

            if (null === $teamOne = $this->teams->findByNameAndSport($gameView->teamOne, $sport->id())) {
                /** Here may be saving logs or errors for output to the client. */
                continue;
            }

            if (null === $teamTwo = $this->teams->findByNameAndSport($gameView->teamTwo, $sport->id())) {
                /** Here may be saving logs or errors for output to the client. */
                continue;
            }

            if (null === $source = $this->sources->findByUrl($gameView->source)) {
                /** Here may be saving logs or errors for output to the client. */
                continue;
            }

            $startDate = (new \DateTimeImmutable($gameView->startDate));

            $game = $this->games->findByLeagueAndTeams(
                $league->id(),
                $teamOne->id(),
                $teamTwo->id(),
                $startDate,
                $sport->minIntervalBetweenGames()
            );

            $newGame = false;

            if ($game === null) {
                $newGame = true;

                $game = new Game(GameId::next(), $league, $teamOne, $teamTwo, $startDate, new \DateTimeImmutable());

                $this->games->add($game);
            }

            if (
                $newGame ||
                $this->gameSources->findOneBy($game->id(), $language->id(), $source->id(), $startDate) === null
            ) {
                $gameSource = new GameSource(
                    GameSourceId::next(),
                    $game,
                    $language,
                    $source,
                    $startDate,
                    new \DateTimeImmutable()
                );

                $this->gameSources->add($gameSource);
            }

            $this->flusher->flush();
        }
    }
}
