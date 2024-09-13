<?php

namespace App\Repositories;

use App\Models\User;

class UserRepository
{
    protected $model;

    public function __construct(User $user)
    {
        $this->model = $user;
    }

    public function getAll($active = null, $role = null)
    {
        $query = $this->model->newQuery();

        if ($active !== null) {
            $etat = $active === 'oui' ? 'ACTIF' : 'INACTIF';
            $query->where('etat', $etat);
        }

        if ($role !== null) {
            $query->whereHas('role', function ($query) use ($role) {
                $query->where('name', $role);
            });
        }

        return $query->get();
    }

    public function findById(int $id)
    {
        return $this->model->find($id);
    }

    public function create(array $data): User
    {
        return $this->model->create($data);
    }

    public function delete($id)
{
    // Trouver l'utilisateur par ID
    $user = User::find($id);

    // Vérifiez si l'utilisateur existe avant d'appeler delete()
    if ($user) {
        $user->delete();
        return true;
    } else {
        // Gestion des cas où l'utilisateur n'est pas trouvé
        return false;
    }
}

}
