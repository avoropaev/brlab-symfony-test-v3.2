<?php

declare(strict_types=1);

namespace App\Model\Entity\Game;

use App\Model\Entity\Team\Id as TeamId;
use App\Model\Entity\League\Id as LeagueId;
use App\Model\EntityNotFoundException;
use Doctrine\ORM\EntityManagerInterface;

class GameRepository
{
    /**
     * @var \Doctrine\ORM\EntityRepository
     */
    private $repo;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * GameRepository constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->repo = $em->getRepository(Game::class);
        $this->em = $em;
    }

    /**
     * @param LeagueId $leagueId
     * @param TeamId $teamOneId
     * @param TeamId $teamTwoId
     * @param \DateTimeImmutable $startDate
     * @param int $minIntervalBetweenGames
     * @return Game|null
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findByLeagueAndTeams(
        LeagueId $leagueId,
        TeamId $teamOneId,
        TeamId $teamTwoId,
        \DateTimeImmutable $startDate,
        int $minIntervalBetweenGames
    ): ?Game {
        $startDateStart = $startDate->modify('-' . floor($minIntervalBetweenGames / 2) . 'minutes');
        $startDateEnd = $startDate->modify('+' . floor($minIntervalBetweenGames / 2) . 'minutes');

        $query = $this->repo->createQueryBuilder('e')
            ->select('e')
            ->andWhere('e.league = :league_id')
            ->andWhere('e.teamOne = :team_one_id')
            ->andWhere('e.teamTwo = :team_two_id')
            ->andWhere('e.startDate >= :start_date_start AND e.startDate <= :start_date_end')
            ->setParameter(':league_id', $leagueId->getValue())
            ->setParameter(':team_one_id', $teamOneId->getValue())
            ->setParameter(':team_two_id', $teamTwoId->getValue())
            ->setParameter(':start_date_start', $startDateStart)
            ->setParameter(':start_date_end', $startDateEnd)
            ->getQuery();

        /** @var Game|null $entity */
        $entity = $query->getOneOrNullResult();

        return $entity;
    }

    /**
     * @param Id $id
     * @return Game
     */
    public function get(Id $id): Game
    {
        /** @var Game $entity */
        if (!$entity = $this->repo->find($id->getValue())) {
            throw new EntityNotFoundException('Game is not found.');
        }

        return $entity;
    }

    /**
     * @param Game $entity
     */
    public function add(Game $entity): void
    {
        $this->em->persist($entity);
    }

    /**
     * @param Game $entity
     */
    public function remove(Game $entity): void
    {
        $this->em->remove($entity);
    }
}
