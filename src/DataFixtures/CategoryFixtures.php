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
     * Ref to the category surnamed HP
     */
    const HP_REFERENCE = 'HP';
    /**
     * Ref to the category surnamed CHQ
     */
    const DEPOT_CHQ_REFERENCE = 'DEPOT_CHQ';
    /**
     * Ref to the category surnamed TTFC
     */
    const TTFC_REFERENCE = 'TTFC';
    /**
     * Ref to the category surnamed TTTC
     */
    const TTTC_REFERENCE = 'TTTC';

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $parent = new Category();
        $parent->setTtfc();
        $parent->setName('Placement');
        $manager->persist($parent);

        $this->addReference(self::TTFC_REFERENCE, $parent);

        $parent = new Category();
        $parent->setTttc();
        $parent->setName('Réapprovisionnement');
        $manager->persist($parent);

        $this->addReference(self::TTTC_REFERENCE, $parent);

        // Credit

        // Offre de remboursement : Hp / Karcher / Samsung

        $parent = new Category();
        $parent->setCredit();
        $parent->setName('Offre de remboursement');

        $children = new Category();
        $children->setName('Hp');
        $children->setLogo('hp.jpg');
        $manager->persist($children);
        $parent->addChildren($children);

        $this->addReference(self::HP_REFERENCE, $children);

        $children = new Category();
        $children->setName('Karcher');
        $manager->persist($children);
        $parent->addChildren($children);

        $children = new Category();
        $children->setName('Samsung');
        $children->setLogo('samsung.png');
        $manager->persist($children);
        $parent->addChildren($children);

        $manager->persist($parent);

        // Remboursement santé : Assurance maladie / Mutuelle

        $parent = new Category();
        $parent->setCredit();
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
        $parent->setCredit();
        $parent->setName('Dépôt');

        $children = new Category();
        $children->setName('Chèque');
        $children->setLogo('cheque.png');
        $manager->persist($children);
        $parent->addChildren($children);

        $this->addReference(self::DEPOT_CHQ_REFERENCE, $children);

        $children = new Category();
        $children->setName('Espèce');
        $children->setLogo('espece.jpg');
        $manager->persist($children);
        $parent->addChildren($children);

        $manager->persist($parent);

        // Debit

        // Supermarché : Leclerc / Auchan / Carrefour

        $parent = new Category();
        $parent->setDebit();
        $parent->setName('Supermarché');

        $children = new Category();
        $children->setName('Leclerc');
        $children->setLogo('leclerc.jpeg');
        $manager->persist($children);
        $parent->addChildren($children);

        $children = new Category();
        $children->setName('Auchan');
        $children->setLogo('auchan.jpg');
        $manager->persist($children);
        $parent->addChildren($children);

        $children = new Category();
        $children->setName('Carrefour');
        $children->setLogo('carrefour.png');
        $manager->persist($children);
        $parent->addChildren($children);

        $manager->persist($parent);

        // Habitation : Prêt immobilier / Assurance prêt

        $parent = new Category();
        $parent->setDebit();
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
