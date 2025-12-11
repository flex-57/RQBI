<?php

namespace App\Security\Voter;

use App\Entity\Page;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

/**
 * @extends Voter<String, Page>
 */
class PageVoter extends Voter
{
    public const VIEW = 'view';
    public const EDIT = 'edit';
    public const DELETE = 'delete';

    protected function supports(string $attribute, $subject): bool
    {
        return in_array($attribute, [self::VIEW, self::EDIT, self::DELETE])
            && $subject instanceof Page;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        // if the user is anonymous, do not grant access
        if (!$user instanceof User) {
            return false;
        }

        return match ($attribute) {
            self::VIEW => $this->canView($subject, $user),
            self::EDIT, self::DELETE => $this->canEdit($subject, $user),
            default => false,
        };
    }

    private function canView(Page $page, User $user): bool
    {
        if (in_array('ROLE_ADMIN', $user->getRoles())) {
            return true;
        }

        return $page->isPublished();
    }

    private function canEdit(Page $page, User $user): bool
    {
        return in_array('ROLE_ADMIN', $user->getRoles());
    }
}
