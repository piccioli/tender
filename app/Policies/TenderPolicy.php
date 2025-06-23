<?php

namespace App\Policies;

use App\Models\Tender;
use App\Models\User;

class TenderPolicy
{
    public function delete(User $user, Tender $tender)
    {
        return $user->hasRole('admin');
    }

    public function viewAny(User $user)
    {
        return true;
    }

    public function view(User $user, Tender $tender)
    {
        return true;
    }

    public function update(User $user, Tender $tender)
    {
        if ($user->hasRole('admin')) {
            return true;
        }
        // Se non c'è editor, solo il creator può modificare
        if (is_null($tender->user_editor_id)) {
            return $user->id === $tender->user_creator_id;
        }
        // Se c'è editor, solo l'editor può modificare (non il creator)
        return $user->id === $tender->user_editor_id;
    }
} 