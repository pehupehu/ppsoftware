<?php

namespace App\Entity\Financial;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Table(name="banks")
 * @ORM\Entity(repositoryClass="App\Repository\Financial\BankRepository")
 * @UniqueEntity("surname")
 */
class Bank
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
     * @ORM\Column(type="string", nullable=true)
     * @var string
     */
    private $logo;

    /**
     * @ORM\OneToMany(targetEntity="Account", mappedBy="bank")
     * @var ArrayCollection
     */
    private $accounts;

    /**
     * Bank constructor.
     */
    public function __construct()
    {
        $this->accounts = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return Bank
     */
    public function setId(int $id): Bank
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
     * @return Bank
     */
    public function setName(string $name): Bank
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
     * @return Bank
     */
    public function setSurname(string $surname): Bank
    {
        $this->surname = $surname;
        return $this;
    }

    /**
     * @return string
     */
    public function getLogo()
    {
        return $this->logo ?? '';
    }

    /**
     * @param string $logo
     * @return Bank
     */
    public function setLogo(string $logo): Bank
    {
        $this->logo = $logo;
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
