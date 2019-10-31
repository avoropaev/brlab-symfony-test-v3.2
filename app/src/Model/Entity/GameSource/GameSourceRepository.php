<?php

declare(strict_types=1);

namespace App\Model\Entity\GameSource;

use App\Model\EntityNotFoundException;
use Doctrine\ORM\EntityManagerInterface;
use App\Model\Entity\Source\Id as SourceId;
use App\Model\Entity\Language\Id as LanguageId;
use App\Model\Entity\Game\Id as GameId;

class GameSourceRepository
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
     * GameSourceRepository constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->repo = $em->getRepository(GameSource::class);
        $this->em = $em;
    }

    /**
     * @param GameId $gameId
     * @param LanguageId $languageId
     * @param SourceId $sourceId
     * @param \DateTimeImmutable $startDate
     * @return GameSource|null
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findOneBy(
        GameId $gameId,
        LanguageId $languageId,
        SourceId $sourceId,
        \DateTimeImmutable $startDate
    ): ?GameSource {

        $query = $this->repo->createQueryBuilder('e')
            ->select('e')
            ->andWhere('e.game = :game_id')
            ->andWhere('e.language = :language_id')
            ->andWhere('e.source = :source_id')
            ->andWhere('e.startDate = :start_date')
            ->setParameter(':game_id', $gameId->getValue())
            ->setParameter(':language_id', $languageId->getValue())
            ->setParameter(':source_id', $sourceId->getValue())
            ->setParameter(':start_date', $startDate)
            ->getQuery();

        /** @var GameSource|null $entity */
        $entity = $query->getOneOrNullResult();

        return $entity;
    }

    /**
     * @param Id $id
     * @return GameSource
     */
    public function get(Id $id): GameSource
    {
        /** @var GameSource $entity */
        if (!$entity = $this->repo->find($id->getValue())) {
            throw new EntityNotFoundException('Game source is not found.');
        }

        return $entity;
    }

    /**
     * @param GameSource $entity
     */
    public function add(GameSource $entity): void
    {
        $this->em->persist($entity);
    }

    /**
     * @param GameSource $entity
     */
    public function remove(GameSource $entity): void
    {
        $this->em->remove($entity);
    }
}
