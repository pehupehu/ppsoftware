<?php

namespace App\Entity\Financial;

use App\Entity\User;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="transactions")
 * @ORM\Entity(repositoryClass="App\Repository\Financial\TransactionRepository")
 */
class Transaction
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
     * @ORM\ManyToOne(targetEntity="Account")
     * @ORM\JoinColumn(name="account_id", referencedColumnName="id")
     * @var Account
     */
    private $account;

    /**
     * @ORM\Column(type="date")
     * @var \DateTime
     */
    private $date;

    /**
     * @ORM\Column(name="created_at", type="datetime", options={"default": "CURRENT_TIMESTAMP"})
     * @var \DateTime
     */
    private $createdAt;

    /**
     * @ORM\Column(type="string", nullable=true)
     * @var string
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=10)
     * @var string
     */
    private $type;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2)
     * @var float
     */
    private $amount;

    /**
     * @ORM\Column(type="integer", nullable=true, options={"unsigned": true})
     * @var integer
     */
    private $cheque_number;

    /**
     * @ORM\ManyToOne(targetEntity="TypeOfTransaction")
     * @ORM\JoinColumn(name="type_of_transaction_id", referencedColumnName="id")
     * @var TypeOfTransaction
     */
    private $typeOfTransaction;

    /**
     * @ORM\ManyToOne(targetEntity="Category")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id")
     * @var Category
     */
    private $category;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     * @ORM\JoinColumn(name="tick_user_id", referencedColumnName="id")
     * @var User
     */
    private $tickUser;

    /**
     * @ORM\Column(name="tick_date", type="datetime", nullable=true)
     * @var \DateTime
     */
    private $tickDate;

    /**
     * @ORM\ManyToOne(targetEntity="Transfer")
     * @ORM\JoinColumn(name="transfer_id", referencedColumnName="id")
     * @var Transfer
     */
    private $transfer;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return Transaction
     */
    public function setId(int $id): Transaction
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
     * @return Transaction
     */
    public function setCreator(User $creator): Transaction
    {
        $this->creator = $creator;
        return $this;
    }

    /**
     * @return Account
     */
    public function getAccount(): Account
    {
        return $this->account;
    }

    /**
     * @param Account $account
     * @return Transaction
     */
    public function setAccount(Account $account): Transaction
    {
        $this->account = $account;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getDate(): \DateTime
    {
        return $this->date;
    }

    /**
     * @param \DateTime $date
     * @return Transaction
     */
    public function setDate(\DateTime $date): Transaction
    {
        $this->date = $date;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTime $createdAt
     * @return Transaction
     */
    public function setCreatedAt(\DateTime $createdAt): Transaction
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return Transaction
     */
    public function setName(string $name): Transaction
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     * @return Transaction
     */
    public function setType(string $type): Transaction
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return bool
     */
    public function isCredit()
    {
        return $this->type === Category::CREDIT;
    }

    /**
     * @return Transaction
     */
    public function setCredit(): Transaction
    {
        $this->type = Category::CREDIT;
        return $this;
    }

    /**
     * @return bool
     */
    public function isDebit()
    {
        return $this->type === Category::DEBIT;
    }

    /**
     * @return Transaction
     */
    public function setDebit(): Transaction
    {
        $this->type = Category::DEBIT;
        return $this;
    }

    /**
     * @return float
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @param float $amount
     * @return Transaction
     */
    public function setAmount(float $amount): Transaction
    {
        $this->amount = $amount;
        return $this;
    }

    /**
     * @return int
     */
    public function getChequeNumber()
    {
        return $this->cheque_number;
    }

    /**
     * @param int $cheque_number
     * @return Transaction
     */
    public function setChequeNumber(int $cheque_number): Transaction
    {
        $this->cheque_number = $cheque_number;
        return $this;
    }

    /**
     * @return TypeOfTransaction
     */
    public function getTypeOfTransaction()
    {
        return $this->typeOfTransaction;
    }

    /**
     * @param TypeOfTransaction $typeOfTransaction
     * @return Transaction
     */
    public function setTypeOfTransaction(TypeOfTransaction $typeOfTransaction): Transaction
    {
        $this->typeOfTransaction = $typeOfTransaction;
        return $this;
    }

    /**
     * @return Category
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @param Category $category
     * @return Transaction
     */
    public function setCategory(Category $category): Transaction
    {
        $this->category = $category;
        return $this;
    }

    /**
     * @return User
     */
    public function getTickUser(): User
    {
        return $this->tickUser;
    }

    /**
     * @param User $tickUser
     * @return Transaction
     */
    public function setTickUser(User $tickUser): Transaction
    {
        $this->tickUser = $tickUser;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getTickDate()
    {
        return $this->tickDate;
    }

    /**
     * @param \DateTime $tickDate
     * @return Transaction
     */
    public function setTickDate(\DateTime $tickDate): Transaction
    {
        $this->tickDate = $tickDate;
        return $this;
    }

    /**
     * @return Transfer
     */
    public function getTransfer()
    {
        return $this->transfer;
    }

    /**
     * @param Transfer $transfer
     * @return Transaction
     */
    public function setTransfer(Transfer $transfer): Transaction
    {
        $this->transfer = $transfer;
        return $this;
    }

    public function getJsonData()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'year' => $this->date->format('Y'),
            'yearsday' => $this->date->format('z'),
        ];
    }
}
