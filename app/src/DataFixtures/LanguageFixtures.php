<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Model\Entity\Language\Language;
use App\Model\Entity\Language\Id;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class LanguageFixtures extends Fixture
{
    public const REFERENCE_RUSSIAN = 'russian';
    public const REFERENCE_ENGLISH = 'english';

    /**
     * @param ObjectManager $manager
     * @throws \Exception
     */
    public function load(ObjectManager $manager)
    {
        $language = new Language(Id::next(), 'Русский', new \DateTimeImmutable(), [
            'русский',
            'ru',
            'rus',
            'russian'
        ]);
        $manager->persist($language);
        $this->setReference(self::REFERENCE_RUSSIAN, $language);

        $language = new Language(Id::next(), 'Английский', new \DateTimeImmutable(), [
            'английский',
            'en',
            'eng',
            'english'
        ]);
        $manager->persist($language);
        $this->setReference(self::REFERENCE_ENGLISH, $language);

        $manager->flush();
    }
}
