<?php

namespace App\Entity\Financial;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="transaction_types")
 * @ORM\Entity(repositoryClass="App\Repository\Financial\TypeOfTransactionRepository")
 */
class TypeOfTransaction
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @var integer
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     * @var string
     */
    private $name;

    /**
     * @ORM\Column(type="string")
     * @var string
     */
    private $surname;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return TypeOfTransaction
     */
    public function setId(int $id): TypeOfTransaction
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return TypeOfTransaction
     */
    public function setName(string $name): TypeOfTransaction
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getSurname(): string
    {
        return $this->surname;
    }

    /**
     * @param string $surname
     * @return TypeOfTransaction
     */
    public function setSurname(string $surname): TypeOfTransaction
    {
        $this->surname = $surname;
        return $this;
    }

    /**
     * @return bool
     */
    public function canBeRemove(): bool
    {
        return true;
    }

    /**
     * @return bool
     */
    public function remove(): bool
    {
        if (!$this->canBeRemove()) {
            return false;
        }

        return true;
    }
}
