<?php

namespace App\DataFixtures;

use App\Entity\Financial\Account;
use App\Entity\Financial\Category;
use App\Entity\Financial\Transaction;
use App\Entity\Financial\TypeOfTransaction;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Class TransactionFixtures
 * @package App\DataFixtures
 */
class TransactionFixtures extends Fixture implements DependentFixtureInterface
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        /** @var User $admin */
        $admin = $this->getReference(UserFixtures::ADMIN_REFERENCE);
        /** @var TypeOfTransaction $cb */
        $cb = $this->getReference(TypeOfTransactionFixtures::CB_REFERENCE);
        /** @var TypeOfTransaction $chq */
        $chq = $this->getReference(TypeOfTransactionFixtures::CHQ_REFERENCE);
        /** @var Account $ccca */
        $ccca = $this->getReference(AccountFixtures::CCCA_REFERENCE);
        /** @var Account $lac */
        $lac = $this->getReference(AccountFixtures::LAC_REFERENCE);
        /** @var Category $depot_chq */
        $depot_chq = $this->getReference(CategoryFixtures::DEPOT_CHQ_REFERENCE);
        /** @var Category $hp */
        $hp = $this->getReference(CategoryFixtures::HP_REFERENCE);

        $transaction = new Transaction();
        $transaction->setCreator($admin);
        $transaction->setCredit(true);
        $transaction->setName('Ouverture du compte');
        $transaction->setDate(new \DateTime('2018-01-01'));
        $transaction->setAccount($ccca);
        $transaction->setCreatedAt(new \DateTime('2018-01-01 20:00:00'));
        $transaction->setAmount(300);
        $transaction->setCategory($depot_chq);
        $transaction->setTypeOfTransaction($chq);
        $manager->persist($transaction);

        $transaction = new Transaction();
        $transaction->setCreator($admin);
        $transaction->setDebit(true);
        $transaction->setName('Cartouche HP');
        $transaction->setDate(new \DateTime('2018-01-02'));
        $transaction->setAccount($ccca);
        $transaction->setCreatedAt(new \DateTime('2018-01-02 20:00:00'));
        $transaction->setAmount(10);
        $transaction->setCategory($hp);
        $transaction->setTypeOfTransaction($cb);
        $manager->persist($transaction);

        $transaction = new Transaction();
        $transaction->setCreator($admin);
        $transaction->setCredit(true);
        $transaction->setName('Ouverture du compte');
        $transaction->setDate(new \DateTime('2018-01-03'));
        $transaction->setAccount($lac);
        $transaction->setCreatedAt(new \DateTime('2018-01-03 20:00:00'));
        $transaction->setAmount(10);
        $transaction->setCategory($depot_chq);
        $transaction->setTypeOfTransaction($chq);
        $manager->persist($transaction);

        $manager->flush();
    }

    /**
     * @return array
     */
    public function getDependencies()
    {
        return [
            TypeOfTransactionFixtures::class,
            AccountFixtures::class,
            CategoryFixtures::class,
        ];
    }
}
