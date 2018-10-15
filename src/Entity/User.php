<?php

namespace App\Entity;

use App\Entity\Financial\Account;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Table(name="users")
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @UniqueEntity("username")
 */
class User implements UserInterface, \Serializable
{
    /**
     *
     */
    const ROLE_ADMIN = 'ROLE_ADMIN';
    /**
     *
     */
    const ROLE_USER = 'ROLE_USER';

    /**
     *
     */
    const ACTIVE = 1;
    /**
     *
     */
    const DISABLE = 0;

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @var integer
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     * @var string
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=64)
     * @var string
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255)
     * @var string
     */
    private $firstname;

    /**
     * @ORM\Column(type="string", length=255)
     * @var string
     */
    private $lastname;

    /**
     * @ORM\Column(type="string", length=255)
     * @var string
     */
    private $role;

    /**
     * @ORM\Column(type="string", length=2)
     * @var string
     */
    private $locale;

    /**
     * @ORM\Column(name="is_active", type="boolean")
     * @var boolean
     */
    private $isActive;

    /**
     * @ORM\Column(name="created_at", type="datetime", options={"default": "CURRENT_TIMESTAMP"})
     * @var \DateTime
     */
    private $createdAt;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Financial\Account", inversedBy="users")
     * @ORM\JoinTable(name="users_accounts")
     * @var ArrayCollection
     */
    private $accounts;

    /**
     * User constructor.
     */
    public function __construct()
    {
        $this->isActive = true;

        $this->accounts = new \Doctrine\Common\Collections\ArrayCollection();

        // may not be needed, see section on salt below
        // $this->salt = md5(uniqid('', true));
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return User
     */
    public function setId(int $id): User
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return null|string
     */
    public function getUsername(): ?string
    {
        return $this->username;
    }

    /**
     * @param string $username
     * @return User
     */
    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * @param string $password
     * @return User
     */
    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    /**
     * @param string $firstname
     * @return User
     */
    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    /**
     * @param string $lastname
     * @return User
     */
    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getRole(): ?string
    {
        return $this->role;
    }

    /**
     * @param string $role
     * @return User
     */
    public function setRole(string $role): self
    {
        $this->role = $role;

        return $this;
    }

    /**
     * @return array
     */
    public function getRoles(): array
    {
        return [$this->role];
    }

    /**
     * @return string
     */
    public function getLocale(): string
    {
        return $this->locale;
    }

    /**
     * @param string $locale
     * @return User
     */
    public function setLocale(string $locale): User
    {
        $this->locale = $locale;
        return $this;
    }

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->isActive == self::ACTIVE;
    }

    /**
     * @param bool $isActive
     * @return User
     */
    public function setIsActive(bool $isActive): self
    {
        $this->isActive = $isActive;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getSalt()
    {
        // you *may* need a real salt depending on your encoder
        // see section on salt below
        return null;
    }

    /**
     *
     */
    public function eraseCredentials()
    {
    }

    /**
     * @return bool
     */
    public function isAccountNonExpired()
    {
        return true;
    }

    /**
     * @return bool
     */
    public function isAccountNonLocked()
    {
        return true;
    }

    /**
     * @return bool
     */
    public function isCredentialsNonExpired()
    {
        return true;
    }

    /** @see \Serializable::serialize() */
    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->username,
            $this->password,
            $this->isActive,
            // see section on salt below
            // $this->salt,
        ));
    }

    /** @see \Serializable::unserialize() */
    public function unserialize($serialized)
    {
        list (
            $this->id,
            $this->username,
            $this->password,
            $this->isActive,
            // see section on salt below
            // $this->salt
            ) = unserialize($serialized);
    }

    /**
     * @return array
     */
    public static function getRolesChoices()
    {
        return [
            'role_admin' => self::ROLE_ADMIN,
            'role_user' => self::ROLE_USER,
        ];
    }

    /**
     * @return bool|null
     */
    public function getIsActive(): ?bool
    {
        return $this->isActive === self::ACTIVE;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTimeInterface $createdAt
     * @return User
     */
    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return array
     */
    public function getAccounts(): array
    {
        return $this->accounts->toArray();
    }

    /**
     * @return ArrayCollection
     */
    public function getAccountsCollection()
    {
        return $this->accounts;
    }

    /**
     * @param Account $account
     * @return User
     */
    public function addAccount(Account $account): User
    {
        if (!$this->getAccountsCollection()->contains($account)) {
            $this->getAccountsCollection()->add($account);
            if (!$account->getUsersCollection()->contains($this)) {
                $account->getUsersCollection()->add($this);
            }
        }
        return $this;
    }

    /**
     * @return bool
     */
    public function canBeEnable(): bool
    {
        return $this->isActive() == self::DISABLE;
    }

    /**
     * @return bool
     */
    public function enable()
    {
        if (!$this->canBeEnable()) {
            return false;
        }

        $this->setIsActive(self::ACTIVE);

        return true;
    }

    /**
     * @return bool
     */
    public function canBeDisable(): bool
    {
        return $this->isActive() == self::ACTIVE;
    }

    /**
     * @return bool
     */
    public function disable()
    {
        if (!$this->canBeDisable()) {
            return false;
        }

        $this->setIsActive(self::DISABLE);

        return true;
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
    public function remove()
    {
        if (!$this->canBeRemove()) {
            return false;
        }

        return true;
    }

    /**
     * @param User $loggedUser
     * @return bool
     */
    public function canBeEditBy(User $loggedUser): bool
    {
        if ($loggedUser->getRole() === self::ROLE_ADMIN) {
            return true;
        }

        return $loggedUser->getId() === $this->getId();
    }

    /**
     * @return string
     */
    public function getDisplayableName()
    {
        return ucfirst(strtolower($this->getFirstname())) . ' ' . strtoupper($this->getLastname());
    }
    
    public function __toString()
    {
        return $this->getDisplayableName();
    }
}
