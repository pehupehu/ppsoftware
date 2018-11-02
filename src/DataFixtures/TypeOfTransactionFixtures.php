<?php

namespace App\DataFixtures;

use App\Entity\Financial\TypeOfTransaction;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Class TypeOfTransactionFixtures
 * @package App\DataFixtures
 */
class TypeOfTransactionFixtures extends Fixture
{
    /**
     * Ref to the type surnamed VIR
     */
    const VIR_REFERENCE = 'VIR';
    /**
     * Ref to the type surnamed CB
     */
    const CB_REFERENCE = 'CB';
    /**
     * Ref to the type surnamed CHQ
     */
    const CHQ_REFERENCE = 'CHQ';

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $typeOfTransaction = new TypeOfTransaction();
        $typeOfTransaction->setName('Virement');
        $typeOfTransaction->setSurname('VIR');
        $typeOfTransaction->setLogo('vir.png');
        $manager->persist($typeOfTransaction);

        $this->addReference(self::VIR_REFERENCE, $typeOfTransaction);

        $typeOfTransaction = new TypeOfTransaction();
        $typeOfTransaction->setName('Carte bancaire');
        $typeOfTransaction->setSurname('CB');
        $typeOfTransaction->setLogo('cb.png');
        $manager->persist($typeOfTransaction);

        $this->addReference(self::CB_REFERENCE, $typeOfTransaction);

        $typeOfTransaction = new TypeOfTransaction();
        $typeOfTransaction->setName('Chèque');
        $typeOfTransaction->setSurname('CHQ');
        $typeOfTransaction->setLogo('chq.png');
        $manager->persist($typeOfTransaction);

        $this->addReference(self::CHQ_REFERENCE, $typeOfTransaction);

        $manager->flush();
    }
}
