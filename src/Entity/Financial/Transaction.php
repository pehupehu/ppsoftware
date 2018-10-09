<?php

namespace App\Entity\Financial;

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
     * @ORM\Column(type="integer", options={"unsigned": true})
     * @var integer
     */
    private $account_id;

    /**
     * @ORM\ManyToOne(targetEntity="Account")
     * @ORM\JoinColumn(name="account_id", referencedColumnName="id")
     * @var Account
     */
    private $account;

    /**
     * @ORM\Column(type="datetime", options={"default": "CURRENT_TIMESTAMP"})
     * @var \DateTime
     */
    private $date;

    /**
     * @ORM\Column(name="updated_at", type="datetime", options={"columnDefinition": "DATETIME DEFAULT CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP"})
     * @var \DateTime
     */
    private $updatedAt;

    /**
     * @ORM\Column(type="integer", options={"unsigned": true})
     * @var integer
     */
    private $type_of_transaction_id;

    /**
     * @ORM\ManyToOne(targetEntity="TypeOfTransaction")
     * @ORM\JoinColumn(name="type_of_transaction_id", referencedColumnName="id")
     * @var TypeOfTransaction
     */
    private $type_of_transaction;

    /**
     * @ORM\Column(type="integer", options={"unsigned": true})
     * @var integer
     */
    private $tick_user_id;

    private $tick_user;

    /**
     * @ORM\Column(type="datetime")
     * @var \DateTime
     */
    private $tick_date;

    /**
     * @ORM\Column(type="integer", options={"unsigned": true})
     * @var integer
     */
    private $category_id;

    /**
     * @ORM\ManyToOne(targetEntity="Category")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id")
     * @var Category
     */
    private $category;

    /**
     * @ORM\Column(type="string")
     * @var string
     */
    private $name;

    /**
     * @ORM\Column(type="boolean")
     * @var bool
     */
    private $debit_credit;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2)
     * @var float
     */
    private $amount;

    /**
     * @ORM\Column(type="integer", options={"unsigned": true})
     * @var integer
     */
    private $cheque_number;

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
     * @return int
     */
    public function getAccountId(): int
    {
        return $this->account_id;
    }

    /**
     * @param int $account_id
     * @return Transaction
     */
    public function setAccountId(int $account_id): Transaction
    {
        $this->account_id = $account_id;
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
    public function getUpdatedAt(): \DateTime
    {
        return $this->updatedAt;
    }

    /**
     * @param \DateTime $updatedAt
     * @return Transaction
     */
    public function setUpdatedAt(\DateTime $updatedAt): Transaction
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }

    /**
     * @return int
     */
    public function getTypeOfTransactionId(): int
    {
        return $this->type_of_transaction_id;
    }

    /**
     * @param int $type_of_transaction_id
     * @return Transaction
     */
    public function setTypeOfTransactionId(int $type_of_transaction_id): Transaction
    {
        $this->type_of_transaction_id = $type_of_transaction_id;
        return $this;
    }

    /**
     * @return TypeOfTransaction
     */
    public function getTypeOfTransaction(): TypeOfTransaction
    {
        return $this->type_of_transaction;
    }

    /**
     * @param TypeOfTransaction $type_of_transaction
     * @return Transaction
     */
    public function setTypeOfTransaction(TypeOfTransaction $type_of_transaction): Transaction
    {
        $this->type_of_transaction = $type_of_transaction;
        return $this;
    }

    /**
     * @return int
     */
    public function getTickUserId(): int
    {
        return $this->tick_user_id;
    }

    /**
     * @param int $tick_user_id
     * @return Transaction
     */
    public function setTickUserId(int $tick_user_id): Transaction
    {
        $this->tick_user_id = $tick_user_id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTickUser()
    {
        return $this->tick_user;
    }

    /**
     * @param mixed $tick_user
     * @return Transaction
     */
    public function setTickUser($tick_user)
    {
        $this->tick_user = $tick_user;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getTickDate(): \DateTime
    {
        return $this->tick_date;
    }

    /**
     * @param \DateTime $tick_date
     * @return Transaction
     */
    public function setTickDate(\DateTime $tick_date): Transaction
    {
        $this->tick_date = $tick_date;
        return $this;
    }

    /**
     * @return int
     */
    public function getCategoryId(): int
    {
        return $this->category_id;
    }

    /**
     * @param int $category_id
     * @return Transaction
     */
    public function setCategoryId(int $category_id): Transaction
    {
        $this->category_id = $category_id;
        return $this;
    }

    /**
     * @return Category
     */
    public function getCategory(): Category
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
     * @return string
     */
    public function getName(): string
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
     * @return bool
     */
    public function isDebitCredit(): bool
    {
        return $this->debit_credit;
    }

    /**
     * @param bool $debit_credit
     * @return Transaction
     */
    public function setDebitCredit(bool $debit_credit): Transaction
    {
        $this->debit_credit = $debit_credit;
        return $this;
    }

    /**
     * @return float
     */
    public function getAmount(): float
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
    public function getChequeNumber(): int
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
}
