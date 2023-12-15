<?php

namespace App\DataFixtures;

use App\Entity\User;
use DateTimeImmutable;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;

;

class SuperAdminFixtures extends Fixture
{  
    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }
    public function load(ObjectManager $manager): void
    {
        $superAdmin = $this->createSuperAdmin();
        $manager->persist($superAdmin);
        $manager->flush();
    }

    private function createSuperAdmin(): User 
    {
        $superAdmin = new User();

        $superAdmin->setFirstName("Léquipe");
        $superAdmin->setLastName("One Blog");
        $superAdmin->setEmail("one-blog-convivial@gmail.com");
        $superAdmin->setRoles(['ROLE_SUPER_ADMIN', 'ROLE_ADMIN', 'ROLE_USER']);

       $passwordHashed = $this->hasher->hashPassword($superAdmin, '<azerty1234A*>');
       $superAdmin->setPassword($passwordHashed);   

       $superAdmin->setIsVerified(true);
       $superAdmin->setCreatedAt(new DateTimeImmutable());

       return $superAdmin;
    }
}
