<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Random\RandomException;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    public const string USER_PREFIX = 'user_';
    public const string DEFAULT_PASSWORD = 'viosys';

    public function __construct(
        #[Autowire(param: 'security.role_hierarchy.roles')]
        private array $roleHierarchy,
        private UserPasswordHasherInterface $passwordHasher,
    )
    {
    }

    /**
     * @throws RandomException
     */
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();
        foreach ($this->roleHierarchy as $role => $hierarchy) {
            $role_short = str_replace(array('ROLE_', '_'), array('', '-'), $role);
            $role_short = strtolower($role_short);
            $user = new User();
            $user->setPassword($this->passwordHasher->hashPassword($user,self::DEFAULT_PASSWORD))
                ->setRoles([$role])
                ->setEmail($role_short . '@local.host')
                ->setFirstName($faker->firstName)
                ->setLastName($faker->lastName)
            ;
            $manager->persist($user);
            $this->addReference(self::USER_PREFIX. $role_short, $user);
        }
        $manager->flush();
    }
}
