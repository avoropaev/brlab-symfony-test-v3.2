<?php

declare(strict_types=1);

namespace App\Model\Entity\Sport;

use App\Model\EntityNotFoundException;
use Doctrine\ORM\EntityManagerInterface;

class SportRepository
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
     * SportRepository constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->repo = $em->getRepository(Sport::class);
        $this->em = $em;
    }

    /**
     * @param string $name
     * @return Sport|null
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findByName(string $name): ?Sport
    {
        $query = $this->repo->createQueryBuilder('e')
            ->select('e')
            ->innerJoin('e.names', 'n')
            ->andWhere('LOWER(n.value) = :name')
            ->setParameter(':name', mb_strtolower($name))
            ->getQuery();

        /** @var Sport|null $entity */
        $entity = $query->getOneOrNullResult();

        return $entity;
    }

    /**
     * @param Id $id
     * @return Sport
     */
    public function get(Id $id): Sport
    {
        /** @var Sport $entity */
        if (!$entity = $this->repo->find($id->getValue())) {
            throw new EntityNotFoundException('Sport is not found.');
        }

        return $entity;
    }

    /**
     * @param Sport $entity
     */
    public function add(Sport $entity): void
    {
        $this->em->persist($entity);
    }

    /**
     * @param Sport $entity
     */
    public function remove(Sport $entity): void
    {
        $this->em->remove($entity);
    }
}
