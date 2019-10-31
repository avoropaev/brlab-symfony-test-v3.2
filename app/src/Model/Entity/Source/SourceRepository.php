<?php

declare(strict_types=1);

namespace App\Model\Entity\Source;

use App\Model\EntityNotFoundException;
use Doctrine\ORM\EntityManagerInterface;

class SourceRepository
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
     * SourceRepository constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->repo = $em->getRepository(Source::class);
        $this->em = $em;
    }

    /**
     * @param string $url
     * @return Source|null
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findByUrl(string $url): ?Source
    {
        $query = $this->repo->createQueryBuilder('e')
            ->select('e')
            ->andWhere('LOWER(e.url) = :url')
            ->setParameter(':url', mb_strtolower($url))
            ->getQuery();

        /** @var Source|null $entity */
        $entity = $query->getOneOrNullResult();

        return $entity;
    }

    /**
     * @param Id $id
     * @return Source
     */
    public function get(Id $id): Source
    {
        /** @var Source $entity */
        if (!$entity = $this->repo->find($id->getValue())) {
            throw new EntityNotFoundException('Source is not found.');
        }

        return $entity;
    }

    /**
     * @param Source $entity
     */
    public function add(Source $entity): void
    {
        $this->em->persist($entity);
    }

    /**
     * @param Source $entity
     */
    public function remove(Source $entity): void
    {
        $this->em->remove($entity);
    }
}
