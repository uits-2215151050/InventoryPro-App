<?php

namespace App\Security\Voter;

use App\Entity\Inventory;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class InventoryVoter extends Voter
{
    public const EDIT = 'edit';
    public const WRITE = 'write';

    protected function supports(string $attribute, mixed $subject): bool
    {
        return in_array($attribute, [self::EDIT, self::WRITE]) && $subject instanceof Inventory;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        if (!$user instanceof User) {
            return false;
        }

        /** @var Inventory $inventory */
        $inventory = $subject;

        return match ($attribute) {
            self::EDIT => $this->canEdit($inventory, $user),
            self::WRITE => $this->canWrite($inventory, $user),
            default => false,
        };
    }

    private function canEdit(Inventory $inventory, User $user): bool
    {
        // Admins can edit any inventory
        if ($user->isAdmin()) {
            return true;
        }

        // Only the creator can edit
        return $inventory->getCreator() === $user;
    }

    private function canWrite(Inventory $inventory, User $user): bool
    {
        // Admins can write to any inventory
        if ($user->isAdmin()) {
            return true;
        }

        // Creator can always write
        if ($inventory->getCreator() === $user) {
            return true;
        }

        // Check if inventory is public
        if ($inventory->isPublic()) {
            return true;
        }

        // Check if user is in writers list
        return $inventory->getWriters()->contains($user);
    }
}
