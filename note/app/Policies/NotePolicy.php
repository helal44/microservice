<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Note;

class NotePolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    public function view(User $user, Note $note)
    {
       
        return $user->id === $note->user_id;
    }

    public function update(User $user, Note $note)
    {
        
        return $user->id === $note->user_id;
    }

    public function delete(User $user, Note $note)
    {
       
        return $user->id === $note->user_id;
    }
}
