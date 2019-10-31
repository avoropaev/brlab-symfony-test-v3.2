<?php

declare(strict_types=1);

namespace App\Model\Entity\Language;

use App\Model\EntityNotFoundException;
use Doctrine\ORM\EntityManagerInterface;

class LanguageRepository
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
     * LanguageRepository constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->repo = $em->getRepository(Language::class);
        $this->em = $em;
    }

    /**
     * @param Id $id
     * @return Language
     */
    public function get(Id $id): Language
    {
        /** @var Language $entity */
        if (!$entity = $this->repo->find($id->getValue())) {
            throw new EntityNotFoundException('Language is not found.');
        }

        return $entity;
    }

    /**
     * @param string $name
     * @return Language|null
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findByName(string $name): ?Language
    {
        $query = $this->repo->createQueryBuilder('e')
                ->select('e')
                ->innerJoin('e.names', 'n')
                ->andWhere('LOWER(n.value) = :name')
                ->setParameter(':name', mb_strtolower($name))
                ->getQuery();

        /** @var Language|null $entity */
        $entity = $query->getOneOrNullResult();

        return $entity;
    }

    /**
     * @param Language $entity
     */
    public function add(Language $entity): void
    {
        $this->em->persist($entity);
    }

    /**
     * @param Language $entity
     */
    public function remove(Language $entity): void
    {
        $this->em->remove($entity);
    }
}
