<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Model\Entity\Sport\Sport;
use App\Model\Entity\Sport\Id;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class SportFixtures extends Fixture
{
    public const REFERENCE_FOOTBALL = 'football';

    /**
     * @param ObjectManager $manager
     * @throws \Exception
     */
    public function load(ObjectManager $manager)
    {
        $sport = new Sport(Id::next(), 'Футбол', 3120, new \DateTimeImmutable(), [
            'футбол',
            'futbol',
            'football'
        ]);
        $manager->persist($sport);
        $this->setReference(self::REFERENCE_FOOTBALL, $sport);

        $manager->flush();
    }
}
