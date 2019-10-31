<?php

declare(strict_types=1);

namespace App\Model\Entity\Team;

use App\Model\Entity\Sport\Id as SportId;
use App\Model\EntityNotFoundException;
use Doctrine\ORM\EntityManagerInterface;

class TeamRepository
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
     * TeamRepository constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->repo = $em->getRepository(Team::class);
        $this->em = $em;
    }

    /**
     * @param string $name
     * @param SportId $sportId
     * @return Team|null
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findByNameAndSport(string $name, SportId $sportId): ?Team
    {
        $query = $this->repo->createQueryBuilder('e')
            ->select('e')
            ->innerJoin('e.names', 'n')
            ->andWhere('LOWER(n.value) = :name')
            ->andWhere('e.sport = :sport_id')
            ->setParameter(':name', mb_strtolower($name))
            ->setParameter(':sport_id', $sportId->getValue())
            ->getQuery();

        /** @var Team|null $entity */
        $entity = $query->getOneOrNullResult();

        return $entity;
    }

    /**
     * @param Id $id
     * @return Team
     */
    public function get(Id $id): Team
    {
        /** @var Team $entity */
        if (!$entity = $this->repo->find($id->getValue())) {
            throw new EntityNotFoundException('Team is not found.');
        }

        return $entity;
    }

    /**
     * @param Team $entity
     */
    public function add(Team $entity): void
    {
        $this->em->persist($entity);
    }

    /**
     * @param Team $entity
     */
    public function remove(Team $entity): void
    {
        $this->em->remove($entity);
    }
}
