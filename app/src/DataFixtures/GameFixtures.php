<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Model\Entity\Game\Game;
use App\Model\Entity\Game\Id;
use App\Model\Entity\GameSource\Id as GameSourceId;
use App\Model\Entity\GameSource\GameSource;
use App\Model\Entity\Language\Language;
use App\Model\Entity\League\League;
use App\Model\Entity\Source\Source;
use App\Model\Entity\Team\Team;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class GameFixtures extends Fixture implements DependentFixtureInterface
{
    public const REFERENCE_ONE = 'game_one';
    public const REFERENCE_TWO = 'game_two';

    /**
     * @param ObjectManager $manager
     * @throws \Exception
     */
    public function load(ObjectManager $manager)
    {
        /** @var Source $sportData */
        $sportData = $this->getReference(SourceFixtures::REFERENCE_SPORT_DATA);
        /** @var Source $anotherData */
        $anotherData = $this->getReference(SourceFixtures::REFERENCE_ANOTHER_DATA);

        /** @var Language $russian */
        $russian = $this->getReference(LanguageFixtures::REFERENCE_RUSSIAN);
        /** @var Language $english */
        $english = $this->getReference(LanguageFixtures::REFERENCE_ENGLISH);

        /** @var League $uefa */
        $uefa = $this->getReference(LeagueFixtures::REFERENCE_UEFA);

        /** @var Team $barcelona */
        $barcelona = $this->getReference(TeamFixtures::REFERENCE_BARCELONA);
        /** @var Team $realMadrid */
        $realMadrid = $this->getReference(TeamFixtures::REFERENCE_REAL_MADRID);

        $game = new Game(
            Id::next(),
            $uefa,
            $barcelona,
            $realMadrid,
            new \DateTimeImmutable(),
            new \DateTimeImmutable()
        );
        $manager->persist($game);
        $this->setReference(self::REFERENCE_ONE, $game);

        $gameSource = new GameSource(
            GameSourceId::next(),
            $game,
            $russian,
            $sportData,
            $game->startDate(),
            $game->createdAt()
        );
        $manager->persist($gameSource);


        $game = new Game(
            Id::next(),
            $uefa,
            $barcelona,
            $realMadrid,
            new \DateTimeImmutable('+ 10 days'),
            new \DateTimeImmutable('+ 10 days')
        );
        $manager->persist($game);
        $this->setReference(self::REFERENCE_TWO, $game);

        $gameSource = new GameSource(
            GameSourceId::next(),
            $game,
            $english,
            $anotherData,
            $game->startDate(),
            $game->createdAt()
        );
        $manager->persist($gameSource);

        $manager->flush();
    }

    /**
     * @return array
     */
    public function getDependencies(): array
    {
        return [
            LeagueFixtures::class,
            TeamFixtures::class,
            LanguageFixtures::class
        ];
    }
}
