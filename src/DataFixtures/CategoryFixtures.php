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

        $category = new Category();
        $category->setCredit(true);
        $category->setName('Offre de remboursement');

        $children = new Category();
        $children->setName('Hp');
        $manager->persist($children);
        $category->addChildren($children);

        $children = new Category();
        $children->setName('Karcher');
        $manager->persist($children);
        $category->addChildren($children);

        $children = new Category();
        $children->setName('Samsung');
        $manager->persist($children);
        $category->addChildren($children);

        $manager->persist($category);
        
        // Debit

        $category = new Category();
        $category->setDebit(true);
        $category->setName('Supermarché');

        $children = new Category();
        $children->setName('Leclerc');
        $manager->persist($children);
        $category->addChildren($children);

        $children = new Category();
        $children->setName('Auchan');
        $manager->persist($children);
        $category->addChildren($children);

        $children = new Category();
        $children->setName('Carrefour');
        $manager->persist($children);
        $category->addChildren($children);

        $manager->persist($category);

        $manager->flush();
    }
}
