<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Model\Entity\Source\Id;
use App\Model\Entity\Source\Source;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class SourceFixtures extends Fixture
{
    public const REFERENCE_SPORT_DATA = 'sport_data_com';
    public const REFERENCE_ANOTHER_DATA = 'another_data_com';

    /**
     * @param ObjectManager $manager
     * @throws \Exception
     */
    public function load(ObjectManager $manager)
    {
        $source = new Source(Id::next(), 'Sport Data', 'sportdata.com', new \DateTimeImmutable());
        $manager->persist($source);
        $this->setReference(self::REFERENCE_SPORT_DATA, $source);

        $source = new Source(Id::next(), 'Another Data', 'anotherdata.com', new \DateTimeImmutable());
        $manager->persist($source);
        $this->setReference(self::REFERENCE_ANOTHER_DATA, $source);

        $manager->flush();
    }
}
