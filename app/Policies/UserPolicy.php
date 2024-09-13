<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class UserPolicy
{
    /**
     * Détermine si l'utilisateur peut voir n'importe quel utilisateur.
     */
    public function viewAny(User $user)
    {
        return $user->role_id === 1; // Seul l'Admin peut voir tous les utilisateurs
    }

    /**
     * Détermine si l'utilisateur peut voir un utilisateur spécifique.
     */
    public function view(User $user, User $model)
    {
        return $user->id === $model->id || $user->role_id === 1; // Admin ou l'utilisateur lui-même
    }

    /**
     * Détermine si l'utilisateur peut créer d'autres utilisateurs.
     */
    public function create(User $user, ?string $roleType = null)
{
    if ($user->role_id === 1) {
        // Admin peut créer des rôles 1 (Admin) et 2 (Boutiquier)
        return in_array($roleType, ['admin', 'boutiquier']);
    }

    if ($user->role_id === 2) {
        // Boutiquier peut créer des clients (rôle 3)
        return $roleType === 'client';
    }

    // Par défaut, ne pas autoriser
    return false;
}

    
    /**
     * Détermine si l'utilisateur peut mettre à jour un utilisateur spécifique.
     */
    public function update(User $user, User $model)
    {
        return $user->id === $model->id || $user->role_id === 1; // Admin ou l'utilisateur lui-même
    }

    /**
     * Détermine si l'utilisateur peut supprimer un utilisateur spécifique.
     */
    public function delete(User $user, User $model)
    {
        return $user->role_id === 1; // Seul l'Admin peut supprimer un utilisateur
    }
}
