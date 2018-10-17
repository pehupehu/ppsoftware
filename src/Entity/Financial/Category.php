<?php

namespace App\Entity\Financial;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="categories")
 * @ORM\Entity(repositoryClass="App\Repository\Financial\CategoryRepository")
 */
class Category
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @var integer
     */
    private $id;

    /**
     * @ORM\Column(type="boolean")
     * @var bool
     */
    private $debit;

    /**
     * @ORM\Column(type="boolean")
     * @var bool
     */
    private $credit;

    /**
     * @ORM\Column(type="string")
     * @var string
     */
    private $name;

    /**
     * @ORM\Column(type="string", nullable=true)
     * @var string
     */
    private $logo;

    /**
     * @ORM\OneToMany(targetEntity="Category", mappedBy="parent")
     * @var ArrayCollection
     */
    private $childrens;

    /**
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="childrens")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id")
     * @var Category
     */
    private $parent;

    /**
     * Category constructor.
     */
    public function __construct()
    {
        $this->childrens = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return Category
     */
    public function setId(int $id): Category
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return bool
     */
    public function isDebit(): bool
    {
        return $this->debit;
    }

    /**
     * @param bool $debit
     * @return Category
     */
    public function setDebit(bool $debit): Category
    {
        $this->debit = $debit;
        $this->credit = !$debit;
        return $this;
    }

    /**
     * @return bool
     */
    public function isCredit(): bool
    {
        return $this->credit;
    }

    /**
     * @param bool $credit
     * @return Category
     */
    public function setCredit(bool $credit): Category
    {
        $this->credit = $credit;
        $this->debit = !$credit;
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
     * @return Category
     */
    public function setName(string $name): Category
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getLogo(): string
    {
        return $this->logo;
    }

    /**
     * @param string $logo
     * @return Category
     */
    public function setLogo(string $logo): Category
    {
        $this->logo = $logo;
        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getChildrens(): ArrayCollection
    {
        return $this->childrens;
    }

    /**
     * @param ArrayCollection $childrens
     * @return Category
     */
    public function setChildrens(ArrayCollection $childrens): Category
    {
        $this->childrens = $childrens;
        return $this;
    }

    /**
     * @param Category $children
     * @return Category
     */
    public function addChildren(Category $children): Category
    {
        if (!$this->childrens->contains($children)) {
            $children->setParent($this);
            $children->setDebit($this->isDebit());
            $this->childrens->add($children);
        }
        return $this;
    }

    /**
     * @param Category $children
     * @return Category
     */
    public function removeChildren(Category $children): Category
    {
        $this->childrens->removeElement($children);
        return $this;
    }

    /**
     * @return Category
     */
    public function getParent(): Category
    {
        return $this->parent;
    }

    /**
     * @param Category $parent
     * @return Category
     */
    public function setParent(Category $parent): Category
    {
        $this->parent = $parent;
        return $this;
    }
}
