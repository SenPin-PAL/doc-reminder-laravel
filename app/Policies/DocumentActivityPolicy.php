<?php
namespace App\Policies;

use App\Models\DocumentActivity;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class DocumentActivityPolicy
{
    /**
     * Menentukan apakah user bisa mengupdate aktivitas.
     */
    public function update(User $user, DocumentActivity $documentActivity): bool
    {
        return $user->id === $documentActivity->user_id;
    }

    /**
     * Menentukan apakah user bisa menghapus aktivitas.
     */
    public function delete(User $user, DocumentActivity $documentActivity): bool
    {
        return $user->id === $documentActivity->user_id;
    }
}