<?php

namespace App\DataFixtures;

use App\Entity\Financial\Bank;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Class BankFixtures
 * @package App\DataFixtures
 */
class BankFixtures extends Fixture
{
    /**
     * Ref to the bank surnamed CA
     */
    const CA_REFERENCE = 'CA';
    /**
     * Ref to the bank surnamed CE
     */
    const CE_REFERENCE = 'CE';
    /**
     * Ref to the bank surnamed SG
     */
    const SG_REFERENCE = 'SG';

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $bank = new Bank();
        $bank->setName('Crédit Agricole');
        $bank->setSurname('CA');
        $bank->setLogo('ca.png');
        $manager->persist($bank);

        $this->addReference(self::CA_REFERENCE, $bank);

        $bank = new Bank();
        $bank->setName("Caisse d'épargne");
        $bank->setSurname('CE');
        $bank->setLogo('ce.png');
        $manager->persist($bank);

        $this->addReference(self::CE_REFERENCE, $bank);

        $bank = new Bank();
        $bank->setName('Société générale');
        $bank->setSurname('SG');
        $bank->setLogo('sg.jpg');
        $manager->persist($bank);

        $this->addReference(self::SG_REFERENCE, $bank);

        $manager->flush();
    }
}
