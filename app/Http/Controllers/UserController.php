<?php

namespace App\Http\Controllers;

use App\Models\User; 
use Illuminate\Http\Request;


class UserController extends Controller
{
    public function show($id)
    {
        $user = User::find($id);
        return view('user.show', compact('user'));
    }

    public function update(Request $request, User $user)
{
    $this->authorize('update', $user);
    // Logique de mise à jour...
}

}

