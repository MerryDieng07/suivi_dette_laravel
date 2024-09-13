<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePaiementRequest;
use App\Models\Paiement;

class PaiementController extends Controller
{
    public function store(StorePaiementRequest $request)
    {
        // Les données ont déjà été validées
        $paiement = Paiement::create([
            'dette_id' => $request->dette_id,
            'montant' => $request->montant,
        ]);

        return response()->json($paiement, 201);
    }
}
