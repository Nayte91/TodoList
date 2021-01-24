<?php


namespace App\Event;

use App\Entity\Task;
use App\Repository\UserRepository;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\Security;

class TaskListener
{
    private $security;
    private $userRepository;

    public function __construct(Security $security, UserRepository $userRepository)
    {
        $this->security = $security;
        $this->userRepository = $userRepository;
    }

    /** @ORM\PrePersist */
    public function assignOwner(Task $task, LifecycleEventArgs $args)
    {
        if ('cli' == php_sapi_name()) return;

        if (!$this->security->getUser()) {
            $task->setOwner($this->userRepository->findOneBy(['username' => 'anonyme']));
        } else {
            $task->setOwner($this->security->getUser());
        }
    }
}