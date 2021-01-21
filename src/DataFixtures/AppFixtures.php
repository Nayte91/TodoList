<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $defaultuser = new User;
        $defaultuser
            ->setEmail('anonymous@todoandco.com')
            ->setPassword($this->encoder->encodePassword($defaultuser, '1ntr0uv4bl3'))
            ->setUsername('anonyme');
        $manager->persist($defaultuser);
        $manager->flush();
    }
}
