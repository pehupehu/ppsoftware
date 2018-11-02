<?php

namespace App\Entity\Financial;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\PersistentCollection;

/**
 * @ORM\Table(name="categories")
 * @ORM\Entity(repositoryClass="App\Repository\Financial\CategoryRepository")
 */
class Category
{
    const CREDIT = 'credit';
    const DEBIT = 'debit';
    const TTFC = 'ttfc';
    const TTTC = 'tttc';
    
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @var integer
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=10)
     * @var string
     */
    private $type;

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
     * @ORM\OrderBy({"name" = "ASC"})
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
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     * @return Category
     */
    public function setType(string $type): Category
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
     * @return Category
     */
    public function setCredit(): Category
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
     * @return Category
     */
    public function setDebit(): Category
    {
        $this->type = Category::DEBIT;
        return $this;
    }

    /**
     * @return bool
     */
    public function isTtfc()
    {
        return $this->type === Category::TTFC;
    }

    /**
     * @return Category
     */
    public function setTtfc(): Category
    {
        $this->type = Category::TTFC;
        return $this;
    }

    /**
     * @return bool
     */
    public function isTttc()
    {
        return $this->type === Category::TTTC;
    }

    /**
     * @return Category
     */
    public function setTttc(): Category
    {
        $this->type = Category::TTTC;
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
    public function getLogo()
    {
        return $this->logo ?? '';
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
     * @return Category[]|ArrayCollection
     */
    public function getChildrens()
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
            $children->setType($this->getType());
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
     * @return bool
     */
    public function hasChildrens(): bool 
    {
        return $this->childrens->count() > 0;
    }

    /**
     * @return Category
     */
    public function getParent()
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

    /**
     * @return bool
     */
    public function canBeRemove()
    {
        // TODO
        return true;
    }

    /**
     * @return bool
     */
    public function remove()
    {
        if ($this->canBeRemove()) {
            foreach($this->getChildrens() as $children) {
                if (!$children->remove()) {
                    return false;
                }
            }
        }

        return true;
    }
}
