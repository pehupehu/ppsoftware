<?php

namespace App\Entity\Financial;

use App\Entity\User;
use App\Repository\Financial\TransactionRepository;
use Doctrine\Common\Collections\ArrayCollection;
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
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     * @ORM\JoinColumn(name="creator_id", referencedColumnName="id")
     * @var User
     */
    private $creator;

    /**
     * @ORM\ManyToOne(targetEntity="TypeOfAccount")
     * @ORM\JoinColumn(name="type_of_account_id", referencedColumnName="id")
     * @var TypeOfAccount
     */
    private $typeOfAccount;

    /**
     * @ORM\ManyToOne(targetEntity="Bank", inversedBy="accounts")
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
    private $balance_currency;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\User", mappedBy="accounts")
     * @var ArrayCollection
     */
    private $users;

    /**
     * Account constructor.
     */
    public function __construct()
    {
        $this->users = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return Account
     */
    public function setId(int $id): Account
    {
        $this->id = $id;
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
        $this->addUser($creator);
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
    public function getBalanceCurrency(): string
    {
        return $this->balance_currency;
    }

    /**
     * @param string $balance_currency
     * @return Account
     */
    public function setBalanceCurrency(string $balance_currency): Account
    {
        $this->balance_currency = $balance_currency;
        return $this;
    }

    /**
     * @return array
     */
    public function getUsers(): array
    {
        return $this->users->toArray();
    }

    /**
     * @return ArrayCollection
     */
    public function getUsersCollection()
    {
        return $this->users;
    }

    /**
     * @param User $user
     * @return Account
     */
    public function addUser(User $user): Account
    {
        if (!$this->getUsersCollection()->contains($user)) {
            $this->getUsersCollection()->add($user);
            if (!$user->getAccountsCollection()->contains($this)) {
                $user->getAccountsCollection()->add($this);
            }
        }
        return $this;
    }

    /**
     * @param User $user
     * @return Account
     */
    public function removeUser(User $user): Account
    {
        $this->getUsersCollection()->removeElement($user);
        $user->getAccountsCollection()->removeElement($this);
        return $this;
    }

    /**
     * @param User $user
     * @return bool
     */
    public function canBeRemove(User $user): bool
    {
        return $this->canEditAccount($user);

        // Check if account is used
    }

    /**
     * @param User $user
     * @return bool
     */
    public function remove(User $user): bool
    {
        if (!$this->canBeRemove($user)) {
            return false;
        }

        return true;
    }

    /**
     * @param User $user
     * @return bool
     */
    public function isCreator(User $user): bool
    {
        return $this->getCreator() === $user;
    }

    /**
     * @param User $user
     * @return bool
     */
    public function canEditAccount(User $user): bool
    {
        return $this->isCreator($user) OR $this->getUsersCollection()->contains($user);
    }

    /**
     * @param User $user
     * @return bool
     */
    public function canShareAccount(User $user): bool
    {
        return $this->isCreator($user);
    }

    private $lastTransactions;

    /**
     * @return mixed
     */
    public function getLastTransactions()
    {
        return $this->lastTransactions;
    }

    /**
     * @param mixed $lastTransactions
     * @return Account
     */
    public function setLastTransactions($lastTransactions)
    {
        $this->lastTransactions = $lastTransactions;
        return $this;
    }
}
