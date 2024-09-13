<?php

namespace App\Observers;

use App\Models\Dette;
use Illuminate\Support\Facades\DB;

class DetteObserver
{
    /**
     * Handle the Dette "created" event.
     *
     * @param  \App\Models\Dette  $dette
     * @return void
     */
    public function created(Dette $dette)
    {
        // Exécuter une transaction ici
        DB::transaction(function () use ($dette) {
            // Logique de la transaction
            // Par exemple, mise à jour du solde du client après création de la dette
            $client = $dette->client;
            // $client->solde -= $dette->montant;
            $client->save();
        });
    }

}
