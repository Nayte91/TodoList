<?php

namespace App\DataFixtures;

use App\Entity\Task;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures //extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $manager->persist($this->createAnonymousUser());
        $manager->persist($this->createOriginalAdmin());

        $manager->flush();
    }

    private function createAnonymousUser(): User
    {
        $anonymousUser = new User;
        return $anonymousUser
            ->setEmail('anonymous@todoandco.com')
            ->setPassword($this->encoder->encodePassword($anonymousUser, '1ntr0uv4bl3'))
            ->setUsername('anonyme')
            ;
    }

    private function createOriginalAdmin(): User
    {
        $originalAdmin = new User;
        return $originalAdmin
            ->setEmail('admin@changezmoi.fr')
            ->setPassword($this->encoder->encodePassword($originalAdmin, 'admin'))
            ->setAdmin(true)
            ->setUsername('administrateur')
            ;
    }
}
