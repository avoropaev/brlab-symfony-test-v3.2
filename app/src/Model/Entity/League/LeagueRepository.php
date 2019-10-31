<?php

declare(strict_types=1);

namespace App\Model\Entity\League;

use App\Model\EntityNotFoundException;
use Doctrine\ORM\EntityManagerInterface;
use App\Model\Entity\Sport\Id as SportId;

class LeagueRepository
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
     * LeagueRepository constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->repo = $em->getRepository(League::class);
        $this->em = $em;
    }

    /**
     * @param string $name
     * @param SportId $sportId
     * @return League|null
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findByNameAndSport(string $name, SportId $sportId): ?League
    {
        $query = $this->repo->createQueryBuilder('e')
            ->select('e')
            ->innerJoin('e.names', 'n')
            ->andWhere('LOWER(n.value) = :name')
            ->andWhere('e.sport = :sport_id')
            ->setParameter(':name', mb_strtolower($name))
            ->setParameter(':sport_id', $sportId->getValue())
            ->getQuery();

        /** @var League|null $entity */
        $entity = $query->getOneOrNullResult();

        return $entity;
    }

    /**
     * @param Id $id
     * @return League
     */
    public function get(Id $id): League
    {
        /** @var League $entity */
        if (!$entity = $this->repo->find($id->getValue())) {
            throw new EntityNotFoundException('League is not found.');
        }

        return $entity;
    }

    /**
     * @param League $entity
     */
    public function add(League $entity): void
    {
        $this->em->persist($entity);
    }

    /**
     * @param League $entity
     */
    public function remove(League $entity): void
    {
        $this->em->remove($entity);
    }
}
