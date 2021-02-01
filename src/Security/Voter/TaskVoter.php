<?php

namespace App\Security\Voter;

use App\Entity\Task;
use App\Repository\UserRepository;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;

final class TaskVoter extends Voter
{
    private $security;
    private $userRepository;

    public function __construct(Security $security, UserRepository $userRepository)
    {
        $this->security = $security;
        $this->userRepository = $userRepository;
    }

    protected function supports($attribute, $subject)
    {
        return in_array($attribute, ['DELETE'])
            && $subject instanceof Task;
    }

    /** @var Task $subject */
    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $loggedUser = $token->getUser();

        if (!$loggedUser instanceof UserInterface) {
            return false;
        }

        $taskOwner = $subject->getOwner();

        if ($taskOwner->getId() === $this->userRepository->getTheAnonymousUser()->getId()) {
            return $this->security->isGranted('ROLE_ADMIN');
        }

        return $taskOwner === $loggedUser;
    }
}
