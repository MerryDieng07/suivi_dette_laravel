<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'nom' => 'Admin',
            'prenom' => 'Super',
            'login' => 'admin',
            'password' => Hash::make('P@s$ser123'), // Hash du mot de passe
            'role_id' => 1, // Assumant que le rôle admin a un role_id de 1
            'photo' => null, // Peut être null ou une chaîne de caractères si une photo est fournie
        ]);
    }
}
