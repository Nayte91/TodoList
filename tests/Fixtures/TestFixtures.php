<?php

namespace App\Tests\Fixtures;

use App\Entity\Task;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class TestFixtures extends Fixture
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

        $adminUser = $this->createAdminUser();
        $manager->persist($adminUser);

        $basicUser = $this->createBasicUser();
        $manager->persist($basicUser);

        $manager->flush();

        $manager->persist($this->createUnlinkedTask($anonymousUser));
        $manager->persist($this->createAdminTask($adminUser));
        $manager->persist($this->createBasicTask($basicUser));

        $manager->flush();
    }

    private function createAnonymousUser(): User
    {
        $anonymousUser = new User;
        return $anonymousUser
            ->setEmail('anonymous@todoandco.com')
            ->setPassword($this->encoder->encodePassword($anonymousUser, '1ntr0uv4bl3'))
            ->setRoles(['ANONYMOUS'])
            ->setUsername('anonyme');
    }

    private function createAdminUser(): User
    {
        $adminUser = new User;
        return $adminUser
            ->setEmail('admin@changezmoi.fr')
            ->setPassword($this->encoder->encodePassword($adminUser, 'admin'))
            ->setAdmin(true)
            ->setUsername('administrateur');
    }

    private function createBasicUser()
    {
        $basicUser = new User;
        return $basicUser
            ->setEmail('basic@changezmoi.fr')
            ->setPassword($this->encoder->encodePassword($basicUser, 'basic'))
            ->setAdmin(false)
            ->setUsername('utilisateur basique');
    }

    private function createUnlinkedTask(User $anonymous): Task
    {
        $anonymousTask = new Task;
        return $anonymousTask
            ->setTitle('Tache anonyme')
            ->setContent('Cette tache n\'est attachée à aucun utilisateur particulier')
            ->setOwner($anonymous);
    }

    private function createAdminTask(User $admin): Task
    {
        $adminTask = new Task;
        return $adminTask
            ->setTitle('Tache administrateur')
            ->setContent('Cette tache appartient à l\'administrateur.')
            ->setOwner($admin);
    }

    private function createBasicTask(User $user): Task
    {
        $basicTask = new Task;
        return $basicTask
            ->setTitle('Tache classique')
            ->setContent('Cette tache appartient à un utilisateur normal.')
            ->setOwner($user);
    }
}