<?php

namespace App\DataFixtures;

use App\Entity\Task;
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
        $anonymousUser = $this->createAnonymousUser();
        $manager->persist($anonymousUser);

        $admin = $this->createOriginalAdmin();
        $manager->persist($admin);

        $user = $this->createNormalUser();
        $manager->persist($user);

        $manager->flush();

        $manager->persist($this->createUnlinkedTask($anonymousUser));
        $manager->persist($this->createAdminTask($admin));
        $manager->persist($this->createNormalTask($user));

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

    private function createNormalUser()
    {
        $user = new User;
        return $user
            ->setEmail('juste.leblanc@todoandco.com')
            ->setPassword($this->encoder->encodePassword($user, 'juste'))
            ->setAdmin(false)
            ->setUsername('Juste Leblanc')
            ;
    }

    private function createUnlinkedTask(User $anonymous): Task
    {
        $anonymousTask = new Task;
        return $anonymousTask
            ->setTitle('Tache anonyme')
            ->setContent('Cette tache n\'est attachée à aucun utilisateur particulier')
            ->setOwner($anonymous)
            ;
    }

    private function createAdminTask(User $admin): Task
    {
        $adminTask = new Task;
        return $adminTask
            ->setTitle('Tache administrateur')
            ->setContent('Cette tache appartient à l\'administrateur.')
            ->setOwner($admin)
            ;
    }

    private function createNormalTask(User $user): Task
    {
        $adminTask = new Task;
        return $adminTask
            ->setTitle('Tache classique')
            ->setContent('Cette tache appartient à un utilisateur normal.')
            ->setOwner($user)
            ;
    }
}
