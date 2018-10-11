<?php

namespace App\Entity\Financial;

use App\Entity\User;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="accounts")
 * @ORM\Entity(repositoryClass="App\Repository\Financial\AccountRepository")
 */
class Account
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @var integer
     */
    private $id;

    /**
     * @ORM\Column(type="integer", options={"unsigned": true})
     * @var integer
     */
    private $creator_id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     * @ORM\JoinColumn(name="creator_id", referencedColumnName="id")
     * @var User
     */
    private $creator;

    /**
     * @ORM\Column(type="integer", options={"unsigned": true})
     * @var integer
     */
    private $type_of_account_id;

    /**
     * @ORM\ManyToOne(targetEntity="TypeOfAccount")
     * @ORM\JoinColumn(name="type_of_account_id", referencedColumnName="id")
     * @var TypeOfAccount
     */
    private $typeOfAccount;

    /**
     * @ORM\Column(type="integer", options={"unsigned": true})
     * @var integer
     */
    private $bank_id;

    /**
     * @ORM\ManyToOne(targetEntity="Bank")
     * @ORM\JoinColumn(name="bank_id", referencedColumnName="id")
     * @var Bank
     */
    private $bank;

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
     * @ORM\Column(type="string")
     * @var string
     */
    private $description;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2)
     * @var float
     */
    private $initial_balance;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2)
     * @var float
     */
    private $current_balance;

    /**
     * @ORM\Column(type="string")
     * @var string
     */
    private $amount_currency;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return Account
     */
    public function setId(int $id): Account
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return int
     */
    public function getCreatorId(): int
    {
        return $this->creator_id;
    }

    /**
     * @param int $creator_id
     * @return Account
     */
    public function setCreatorId(int $creator_id): Account
    {
        $this->creator_id = $creator_id;
        return $this;
    }

    /**
     * @return User
     */
    public function getCreator(): User
    {
        return $this->creator;
    }

    /**
     * @param User $creator
     * @return Account
     */
    public function setCreator(User $creator): Account
    {
        $this->creator = $creator;
        return $this;
    }

    /**
     * @return int
     */
    public function getTypeOfAccountId(): int
    {
        return $this->type_of_account_id;
    }

    /**
     * @param int $type_of_account_id
     * @return Account
     */
    public function setTypeOfAccountId(int $type_of_account_id): Account
    {
        $this->type_of_account_id = $type_of_account_id;
        return $this;
    }

    /**
     * @return TypeOfAccount
     */
    public function getTypeOfAccount(): TypeOfAccount
    {
        return $this->typeOfAccount;
    }

    /**
     * @param TypeOfAccount $typeOfAccount
     * @return Account
     */
    public function setTypeOfAccount(TypeOfAccount $typeOfAccount): Account
    {
        $this->typeOfAccount = $typeOfAccount;
        return $this;
    }

    /**
     * @return int
     */
    public function getBankId(): int
    {
        return $this->bank_id;
    }

    /**
     * @param int $bank_id
     * @return Account
     */
    public function setBankId(int $bank_id): Account
    {
        $this->bank_id = $bank_id;
        return $this;
    }

    /**
     * @return Bank
     */
    public function getBank(): Bank
    {
        return $this->bank;
    }

    /**
     * @param Bank $bank
     * @return Account
     */
    public function setBank(Bank $bank): Account
    {
        $this->bank = $bank;
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
     * @return Account
     */
    public function setName(string $name): Account
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
     * @return Account
     */
    public function setSurname(string $surname): Account
    {
        $this->surname = $surname;
        return $this;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     * @return Account
     */
    public function setDescription(string $description): Account
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @return float
     */
    public function getInitialBalance(): float
    {
        return $this->initial_balance;
    }

    /**
     * @param float $initial_balance
     * @return Account
     */
    public function setInitialBalance(float $initial_balance): Account
    {
        $this->initial_balance = $initial_balance;
        return $this;
    }

    /**
     * @return float
     */
    public function getCurrentBalance(): float
    {
        return $this->current_balance;
    }

    /**
     * @param float $current_balance
     * @return Account
     */
    public function setCurrentBalance(float $current_balance): Account
    {
        $this->current_balance = $current_balance;
        return $this;
    }

    /**
     * @return string
     */
    public function getAmountCurrency(): string
    {
        return $this->amount_currency;
    }

    /**
     * @param string $amount_currency
     * @return Account
     */
    public function setAmountCurrency(string $amount_currency): Account
    {
        $this->amount_currency = $amount_currency;
        return $this;
    }

    /**
     * @return bool
     */
    public function canBeRemove()
    {
        return true;
    }

    /**
     * @return bool
     */
    public function remove()
    {
        if (!$this->canBeRemove()) {
            return false;
        }

        return true;
    }
}
