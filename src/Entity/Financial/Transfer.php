<?php

namespace App\Entity\Financial;

use App\Entity\User;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="transfers")
 * @ORM\Entity(repositoryClass="App\Repository\Financial\TransferRepository")
 */
class Transfer
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
     * @ORM\Column(type="decimal", precision=10, scale=2)
     * @var float
     */
    private $amount;

    /**
     * @ORM\ManyToOne(targetEntity="Transaction")
     * @ORM\JoinColumn(name="transaction_from", referencedColumnName="id")
     * @var Transaction
     */
    private $transactionFrom;

    /**
     * @ORM\ManyToOne(targetEntity="Account")
     * @ORM\JoinColumn(name="account_from", referencedColumnName="id")
     * @var Account
     */
    private $accountFrom;

    /**
     * @ORM\ManyToOne(targetEntity="Transaction")
     * @ORM\JoinColumn(name="transaction_to", referencedColumnName="id")
     * @var Transaction
     */
    private $transactionTo;

    /**
     * @ORM\ManyToOne(targetEntity="Account")
     * @ORM\JoinColumn(name="account_to", referencedColumnName="id")
     * @var Account
     */
    private $accountTo;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return Transfer
     */
    public function setId(int $id): Transfer
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
     * @return Transfer
     */
    public function setCreator(User $creator): Transfer
    {
        $this->getTransactionFrom()->setCreator($creator);
        $this->getTransactionTo()->setCreator($creator);
        $this->creator = $creator;
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
     * @return Transfer
     */
    public function setDate(\DateTime $date): Transfer
    {
        $this->getTransactionFrom()->setDate($date);
        $this->getTransactionTo()->setDate($date);
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
     * @return Transfer
     */
    public function setCreatedAt(\DateTime $createdAt): Transfer
    {
        $this->getTransactionFrom()->setCreatedAt($createdAt);
        $this->getTransactionTo()->setCreatedAt($createdAt);
        $this->createdAt = $createdAt;
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
     * @return Transfer
     */
    public function setAmount(float $amount): Transfer
    {
        $this->getTransactionFrom()->setAmount($amount);
        $this->getTransactionTo()->setAmount($amount);
        $this->amount = $amount;
        return $this;
    }

    /**
     * @return Transaction
     */
    public function getTransactionFrom(): Transaction
    {
        return $this->transactionFrom;
    }

    /**
     * @param Transaction $transactionFrom
     * @return Transfer
     */
    public function setTransactionFrom(Transaction $transactionFrom): Transfer
    {
        $this->transactionFrom = $transactionFrom;
        return $this;
    }

    /**
     * @return Account
     */
    public function getAccountFrom(): Account
    {
        return $this->accountFrom;
    }

    /**
     * @param Account $accountFrom
     * @return Transfer
     */
    public function setAccountFrom(Account $accountFrom): Transfer
    {
        $this->accountFrom = $accountFrom;
        return $this;
    }

    /**
     * @return Transaction
     */
    public function getTransactionTo(): Transaction
    {
        return $this->transactionTo;
    }

    /**
     * @param Transaction $transactionTo
     * @return Transfer
     */
    public function setTransactionTo(Transaction $transactionTo): Transfer
    {
        $this->transactionTo = $transactionTo;
        return $this;
    }

    /**
     * @return Account
     */
    public function getAccountTo(): Account
    {
        return $this->accountTo;
    }

    /**
     * @param Account $accountTo
     * @return Transfer
     */
    public function setAccountTo(Account $accountTo): Transfer
    {
        $this->accountTo = $accountTo;
        return $this;
    }
}