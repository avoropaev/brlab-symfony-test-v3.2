<?php

declare(strict_types=1);

namespace App\ReadModel\Game;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\FetchMode;

class GameFetcher
{
    /**
     * @var Connection
     */
    private $connection;

    /**
     * GameFetcher constructor.
     * @param Connection $connection
     */
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @param Filter $filter
     * @return GameView|null
     */
    public function findOneRandom(Filter $filter): ?GameView
    {
        $qb = $this->connection->createQueryBuilder()
            ->select('g.id')
            ->from('games', 'g')
            ->innerJoin('g', 'game_buffer', 'b', 'g.id = b.game_id')
            ->innerJoin('b', 'sources', 's', 'b.source_id = s.id')
            ->groupBy('g.id')
            ->setMaxResults(1)
            ->orderBy('RANDOM()');

        if ($filter->source !== null) {
            $qb->andWhere($qb->expr()->eq('s.url', ':source'));
            $qb->setParameter(':source', $filter->source);
        }

        if ($filter->startDateStart !== null) {
            $qb->andWhere($qb->expr()->gte('g.start_date', ':start_date_start'));
            $qb->setParameter(':start_date_start', $filter->startDateStart);
        }

        if ($filter->startDateEnd !== null) {
            $qb->andWhere($qb->expr()->lte('g.start_date', ':start_date_end'));
            $qb->setParameter(':start_date_end', $filter->startDateEnd);
        }

        $stmt = $qb->execute();
        $stmt->setFetchMode(FetchMode::CUSTOM_OBJECT, GameView::class);

        /** @var GameView $gameView */
        $gameView = $stmt->fetch();

        if ($gameView === false) {
            return null;
        }

        $qb = $this->connection->createQueryBuilder()
            ->select(
                'g.id',
                'l.display_name AS league',
                't_one.display_name AS team_one',
                't_two.display_name AS team_two',
                'COUNT(b.id) as buffer_count',
                'g.start_date'
            )
            ->from('games', 'g')
            ->innerJoin('g', 'leagues', 'l', 'g.league_id = l.id')
            ->innerJoin('g', 'teams', 't_one', 'g.team_one_id = t_one.id')
            ->innerJoin('g', 'teams', 't_two', 'g.team_two_id = t_two.id')
            ->innerJoin('g', 'game_buffer', 'b', 'g.id = b.game_id')
            ->andWhere($qb->expr()->eq('g.id', ':game_id'))
            ->setParameter(':game_id', $gameView->id)
            ->groupBy('g.id, l.display_name, t_one.display_name, t_two.display_name, g.start_date')
            ->setMaxResults(1);

        $stmt = $qb->execute();
        $stmt->setFetchMode(FetchMode::CUSTOM_OBJECT, GameView::class);

        $result = $stmt->fetch();

        return $result ?: null;
    }
}
