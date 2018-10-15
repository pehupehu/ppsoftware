<?php

namespace App\DataFixtures;

use App\Entity\Financial\TypeOfAccount;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Class TypeOfAccountFixtures
 * @package App\DataFixtures
 */
class TypeOfAccountFixtures extends Fixture
{
    /**
     * Ref to the type surnamed CC
     */
    const CC_REFERENCE = 'CC';
    /**
     * Ref to the type surnamed LA
     */
    const LA_REFERENCE = 'LA';

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $typeOfAccount = new TypeOfAccount();
        $typeOfAccount->setName('Compte courant');
        $typeOfAccount->setSurname('CC');
        $manager->persist($typeOfAccount);

        $this->addReference(self::CC_REFERENCE, $typeOfAccount);

        $typeOfAccount = new TypeOfAccount();
        $typeOfAccount->setName('Livret A');
        $typeOfAccount->setSurname('LA');
        $manager->persist($typeOfAccount);

        $this->addReference(self::LA_REFERENCE, $typeOfAccount);

        $manager->flush();
    }
}
