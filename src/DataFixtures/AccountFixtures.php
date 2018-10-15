<?php

namespace App\DataFixtures;

use App\Entity\Financial\Account;
use App\Entity\Financial\Bank;
use App\Entity\Financial\TypeOfAccount;
use App\Entity\User;
use App\Tools\Currency;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Class AccountFixtures
 * @package App\DataFixtures
 */
class AccountFixtures extends Fixture implements DependentFixtureInterface
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        /** @var User $admin */
        $admin = $this->getReference(UserFixtures::ADMIN_REFERENCE);
        /** @var User $user */
        $user = $this->getReference(UserFixtures::USER_REFERENCE);
        /** @var Bank $ca */
        $ca = $this->getReference(BankFixtures::CA_REFERENCE);
        /** @var Bank $ce */
        $ce = $this->getReference(BankFixtures::CE_REFERENCE);
        /** @var Bank $sg */
        $sg = $this->getReference(BankFixtures::SG_REFERENCE);
        /** @var TypeOfAccount $cc */
        $cc = $this->getReference(TypeOfAccountFixtures::CC_REFERENCE);
        /** @var TypeOfAccount $la */
        $la = $this->getReference(TypeOfAccountFixtures::LA_REFERENCE);

        $account = new Account();
        $account->setName('Compte Courant Crédit Agricole');
        $account->setSurname('CCCA');
        $account->setBank($ca);
        $account->setTypeOfAccount($cc);
        $account->setInitialBalance(0);
        $account->setCurrentBalance(0);
        $account->setDescription($account->getName());
        $account->setAmountCurrency(Currency::EUR);
        $account->setCreator($admin);
        $account->addUser($user);
        $manager->persist($account);

        $account = new Account();
        $account->setName("Compte Courant Caisse d'épargne");
        $account->setSurname('CCCE');
        $account->setBank($ce);
        $account->setTypeOfAccount($cc);
        $account->setInitialBalance(0);
        $account->setCurrentBalance(0);
        $account->setDescription($account->getName());
        $account->setAmountCurrency(Currency::EUR);
        $account->setCreator($admin);
        $account->addUser($user);
        $manager->persist($account);

        $account = new Account();
        $account->setName("Livret A Cédric");
        $account->setSurname('LAC');
        $account->setBank($ca);
        $account->setTypeOfAccount($la);
        $account->setInitialBalance(0);
        $account->setCurrentBalance(0);
        $account->setDescription($account->getName());
        $account->setAmountCurrency(Currency::EUR);
        $account->setCreator($admin);
        $manager->persist($account);

        $account = new Account();
        $account->setName("Livret A Julie");
        $account->setSurname('LAJ');
        $account->setBank($ca);
        $account->setTypeOfAccount($la);
        $account->setInitialBalance(0);
        $account->setCurrentBalance(0);
        $account->setDescription($account->getName());
        $account->setAmountCurrency(Currency::EUR);
        $account->setCreator($user);
        $manager->persist($account);

        $manager->flush();
    }

    /**
     * @return array
     */
    public function getDependencies()
    {
        return [
            UserFixtures::class,
            BankFixtures::class,
            TypeOfAccountFixtures::class,
        ];
    }
}
