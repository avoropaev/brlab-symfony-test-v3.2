<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Model\Entity\Sport\Sport;
use App\Model\Entity\Team\Id;
use App\Model\Entity\Team\Team;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class TeamFixtures extends Fixture implements DependentFixtureInterface
{
    public const REFERENCE_REAL_MADRID = 'real_madrid';
    public const REFERENCE_BARCELONA = 'barcelona';

    /**
     * @param ObjectManager $manager
     * @throws \Exception
     */
    public function load(ObjectManager $manager)
    {
        /** @var Sport $football */
        $football = $this->getReference(SportFixtures::REFERENCE_FOOTBALL);

        $team = new Team(Id::next(), 'Реал Мадрид', $football, new \DateTimeImmutable(), [
            'реал мадрид',
            'реал',
            'real',
            'real m'
        ]);
        $manager->persist($team);
        $this->setReference(self::REFERENCE_REAL_MADRID, $team);

        $team = new Team(Id::next(), 'Барселона', $football, new \DateTimeImmutable(), [
            'барселона',
            'фк барселона',
            'barcelona'
        ]);
        $manager->persist($team);
        $this->setReference(self::REFERENCE_BARCELONA, $team);

        $manager->flush();
    }

    /**
     * @return array
     */
    public function getDependencies(): array
    {
        return [
            SportFixtures::class
        ];
    }
}
