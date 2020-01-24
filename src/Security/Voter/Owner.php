<?php

declare(strict_types=1);

/*
 * This file is part of the user bundle package.
 * (c) Connect Holland.
 */

namespace ConnectHolland\UserBundle\Security\Voter;

use ConnectHolland\UserBundle\Entity\UserInterface;
use ConnectHolland\UserBundle\Security\Ownable;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class Owner extends Voter
{
    /**
     * @var string
     */
    const VIEW = 'VIEW';

    /**
     * @var string
     */
    const EDIT = 'EDIT';

    /**
     * @var string
     */
    const CREATE = 'CREATE';

    /**
     * @var string
     */
    const DELETE = 'DELETE';

    /**
     * @var array<string>
     */
    protected $attributes = [self::VIEW, self::EDIT, self::CREATE, self::DELETE];

    /**
     * @var array<string>
     */
    protected $roles = ['ROLE_USER'];

    /**
     * @var AccessDecisionManagerInterface
     */
    private $decisionManager;

    public function __construct(AccessDecisionManagerInterface $decisionManager)
    {
        $this->decisionManager = $decisionManager;
    }

    /**
     * @param string $attribute
     * @param mixed  $subject
     */
    protected function supports($attribute, $subject): bool
    {
        // if the attribute isn't one we support, return false
        if (!in_array($attribute, $this->attributes)) {
            return false;
        }

        // only vote on Ownable objects inside this voter
        if (!$subject instanceof Ownable) {
            return false;
        }

        return true;
    }

    /**
     * @param string $attribute
     * @param mixed  $subject
     */
    protected function voteOnAttribute($attribute, $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        if (!$user instanceof UserInterface) {
            // the user must be logged in; if not, deny access
            return false;
        }

        if (!$this->decisionManager->decide($token, $this->roles)) {
            return false;
        }

        // you know $subject is a Ownable object, thanks to supports
        /* @var Ownable $subject */

        switch ($attribute) {
            case self::CREATE:
                return $this->canCreate($subject, $user);
            case self::VIEW:
                return $this->canView($subject, $user);
            case self::EDIT:
                return $this->canEdit($subject, $user);
            case self::DELETE:
                return $this->canDelete($subject, $user);
        }

        throw new \LogicException(sprintf('Unable to handle attribute %2$s. The voter %1$s claimed support for unsupported attribute %2$s', __CLASS__, $attribute));
    }

    private function canCreate(Ownable $subject, UserInterface $user): bool
    {
        return true;
    }

    private function canView(Ownable $subject, UserInterface $user): bool
    {
        // if they can edit, they can view
        if ($this->canEdit($subject, $user)) {
            return true;
        }

        return false;
    }

    /**
     * @return bool
     */
    private function canDelete(Ownable $subject, UserInterface $user)
    {
        // if they can edit, they can delete
        if ($this->canEdit($subject, $user)) {
            return true;
        }

        return false;
    }

    private function canEdit(Ownable $subject, UserInterface $user): bool
    {
        return $subject->getOwners()->contains($user);
    }
}
