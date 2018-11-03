<?php

namespace App\DataFixtures;

use App\Entity\Financial\Account;
use App\Entity\Financial\Category;
use App\Entity\Financial\Transaction;
use App\Entity\Financial\Transfer;
use App\Entity\Financial\TypeOfTransaction;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Class TransferFixtures
 * @package App\DataFixtures
 */
class TransferFixtures extends Fixture implements DependentFixtureInterface
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        /** @var User $admin */
        $admin = $this->getReference(UserFixtures::ADMIN_REFERENCE);
        /** @var TypeOfTransaction $vir */
        $vir = $this->getReference(TypeOfTransactionFixtures::VIR_REFERENCE);
        /** @var Account $ccca */
        $ccca = $this->getReference(AccountFixtures::CCCA_REFERENCE);
        /** @var Account $lac */
        $lac = $this->getReference(AccountFixtures::LAC_REFERENCE);
        /** @var Category $ttfc */
        $ttfc = $this->getReference(CategoryFixtures::TTFC_REFERENCE);
        /** @var Category $tttc */
        $tttc = $this->getReference(CategoryFixtures::TTTC_REFERENCE);

        $transfer = Transfer::create($admin, $ccca, $lac, $ttfc, $tttc, $vir);
        $transfer->setDate(new \DateTime('2018-06-01'));
        $transfer->setAmount(250);
        $manager->persist($transfer);

        $manager->flush();

        $transfer = Transfer::create($admin, $lac, $ccca, $tttc, $ttfc, $vir);
        $transfer->setDate(new \DateTime('2018-06-14'));
        $transfer->setAmount(50);
        $manager->persist($transfer);

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
