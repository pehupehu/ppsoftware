<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class UserFixtures
 * @package App\DataFixtures
 */
class UserFixtures extends Fixture
{
    /**
     * Ref to the admin
     */
    const ADMIN_REFERENCE = 'admin';
    /**
     * Ref to the user
     */
    const USER_REFERENCE = 'user';

    /**
     * @var UserPasswordEncoderInterface
     */
    private $encoder;

    /**
     * UserFixtures constructor.
     * @param UserPasswordEncoderInterface $encoder
     */
    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $admin = new User();
        $admin->setUsername('cedric.peru@gmail.com');
        $admin->setFirstname('Cédric');
        $admin->setLastname('Peru');
        $admin->setLocale('fr');
        $encoded = $this->encoder->encodePassword($admin, 'admin');
        $admin->setPassword($encoded);
        $admin->setIsActive(true);
        $admin->setCreatedAt(new \DateTime());
        $admin->setRole(User::ROLE_ADMIN);
        $manager->persist($admin);

        $this->addReference(self::ADMIN_REFERENCE, $admin);

        $user = new User();
        $user->setUsername('pingaljulie@gmail.com');
        $user->setFirstname('Julie');
        $user->setLastname('Pingal');
        $user->setLocale('fr');
        $encoded = $this->encoder->encodePassword($user, 'user');
        $user->setPassword($encoded);
        $user->setIsActive(true);
        $user->setCreatedAt(new \DateTime());
        $user->setRole(User::ROLE_USER);
        $manager->persist($user);

        $this->addReference(self::USER_REFERENCE, $user);

        $manager->flush();
    }
}
