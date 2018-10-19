<?php

namespace App\DataFixtures;

use App\Entity\Financial\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Class CategoryFixtures
 * @package App\DataFixtures
 */
class CategoryFixtures extends Fixture
{
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
        // Credit

        // Offre de remboursement : Hp / Karcher / Samsung

        $parent = new Category();
        $parent->setCredit(true);
        $parent->setName('Offre de remboursement');

        $children = new Category();
        $children->setName('Hp');
        $manager->persist($children);
        $parent->addChildren($children);

        $children = new Category();
        $children->setName('Karcher');
        $manager->persist($children);
        $parent->addChildren($children);

        $children = new Category();
        $children->setName('Samsung');
        $manager->persist($children);
        $parent->addChildren($children);

        $manager->persist($parent);

        // Remboursement santé : Assurance maladie / Mutuelle

        $parent = new Category();
        $parent->setCredit(true);
        $parent->setName('Remboursement santé');

        $children = new Category();
        $children->setName('Assurance maladie');
        $manager->persist($children);
        $parent->addChildren($children);

        $children = new Category();
        $children->setName('Mutuelle');
        $manager->persist($children);
        $parent->addChildren($children);

        $manager->persist($parent);

        // Dépôt : Chèque / Espèce

        $parent = new Category();
        $parent->setCredit(true);
        $parent->setName('Dépôt');

        $children = new Category();
        $children->setName('Chèque');
        $manager->persist($children);
        $parent->addChildren($children);

        $children = new Category();
        $children->setName('Espèce');
        $manager->persist($children);
        $parent->addChildren($children);

        $manager->persist($parent);

        // Debit

        // Supermarché : Leclerc / Auchan / Carrefour

        $parent = new Category();
        $parent->setDebit(true);
        $parent->setName('Supermarché');

        $children = new Category();
        $children->setName('Leclerc');
        $manager->persist($children);
        $parent->addChildren($children);

        $children = new Category();
        $children->setName('Auchan');
        $manager->persist($children);
        $parent->addChildren($children);

        $children = new Category();
        $children->setName('Carrefour');
        $manager->persist($children);
        $parent->addChildren($children);

        $manager->persist($parent);

        // Habitation : Prêt immobilier / Assurance prêt

        $parent = new Category();
        $parent->setDebit(true);
        $parent->setName('Habitation');

        $children = new Category();
        $children->setName('Prêt immobilier');
        $manager->persist($children);
        $parent->addChildren($children);

        $children = new Category();
        $children->setName('Assurance prêt');
        $manager->persist($children);
        $parent->addChildren($children);

        $manager->persist($parent);

        $manager->flush();
    }
}
