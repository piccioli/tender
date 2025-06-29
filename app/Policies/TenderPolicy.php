<?php

namespace App\Policies;

use App\Models\Tender;
use App\Models\User;

class TenderPolicy
{
    /**
     * Determine whether the user can create models.
     */
    public function create(User $user)
    {
        return $user->hasRole(['admin', 'tender_manager', 'tender_editor']);
    }

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Tender $tender)
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Tender $tender)
    {
        if ($user->hasRole(['admin', 'tender_manager'])) {
            return true;
        }
        // Se non c'è editor, solo il creator può modificare
        if (is_null($tender->user_editor_id)) {
            return $user->id === $tender->user_creator_id;
        }
        // Se c'è editor, solo l'editor può modificare (non il creator)
        return $user->id === $tender->user_editor_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Tender $tender)
    {
        return $user->hasRole(['admin', 'tender_manager']);
    }
} 