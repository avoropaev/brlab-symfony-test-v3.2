<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Model\Entity\League\League;
use App\Model\Entity\League\Id;
use App\Model\Entity\Sport\Sport;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LeagueFixtures extends Fixture implements DependentFixtureInterface
{
    public const REFERENCE_UEFA = 'uefa';

    /**
     * @param ObjectManager $manager
     * @throws \Exception
     */
    public function load(ObjectManager $manager)
    {
        /** @var Sport $football */
        $football = $this->getReference(SportFixtures::REFERENCE_FOOTBALL);

        $league = new League(Id::next(), 'Лига чемпионов УЕФА', $football, new \DateTimeImmutable(), [
            'лига чемпионов уефа',
            'liga uefa',
            'league uefa'
        ]);
        $manager->persist($league);
        $this->setReference(self::REFERENCE_UEFA, $league);

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
