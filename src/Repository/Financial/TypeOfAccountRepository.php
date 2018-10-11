<?php

namespace App\Repository\Financial;

use App\Entity\Financial\TypeOfAccount;
use Doctrine\ORM\EntityRepository;

/**
 * Class TypeOfAccountRepository
 * @package App\Repository\Financial
 */
class TypeOfAccountRepository extends EntityRepository
{
    public function getChoices($name_surname = false)
    {
        $choices = [];

        $query = $this->createQueryBuilder('t')->getQuery();

        /** @var TypeOfAccount $type */
        foreach ($query->execute() as $type) {
            if ($name_surname) {
                $choices[$type->getSurname()] = $type->getId();
            } else {
                $choices[$type->getName()] = $type->getId();
            }
        }

        return $choices;
    }

    public function getTypes()
    {
        dump($this->findAll());
        return $this->findAll();
    }
}
