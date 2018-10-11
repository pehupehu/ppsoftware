<?php

namespace App\Repository\Financial;

use App\Entity\Financial\Bank;
use Doctrine\ORM\EntityRepository;

/**
 * Class BankRepository
 * @package App\Repository\Financial
 */
class BankRepository extends EntityRepository
{
    public function getChoices($name_surname = false)
    {
        $choices = [];

        $query = $this->createQueryBuilder('t')->getQuery();

        /** @var Bank $bank */
        foreach ($query->execute() as $bank) {
            if ($name_surname) {
                $choices[$bank->getSurname()] = $bank->getId();
            } else {
                $choices[$bank->getName()] = $bank->getId();
            }
        }

        return $choices;
    }

    public function getBanks()
    {
        return $this->findAll();
    }
}
