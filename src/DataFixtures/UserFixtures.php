<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\PasswordHasher\PasswordHasherInterface;
use Symfony\Component\Security\Core\Role\RoleHierarchyInterface;

class UserFixtures extends Fixture
{
    const DEFAULT_PASSWORD = 'viosys';

    public function __construct(
        #[Autowire(param: 'security.role_hierarchy.roles')]
        private array $roleHierarchy,
        private UserPasswordHasherInterface $passwordHasher,
    )
    {
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();
        foreach ($this->roleHierarchy as $role => $hierarchy) {
            $role_short = str_replace('ROLE_', '', $role);
            $role_short = str_replace('_', '-', $role_short);
            $role_short = strtolower($role_short);
            $user = new User();
            $user->setPassword($this->passwordHasher->hashPassword($user,self::DEFAULT_PASSWORD))
                ->setRoles([$role])
                ->setEmail($role_short . '@local.host')
                ->setFirstName($faker->firstName)
                ->setLastName($faker->lastName);
            $manager->persist($user);
            $this->addReference($role_short, $user);
        }
        $manager->flush();
    }
}
