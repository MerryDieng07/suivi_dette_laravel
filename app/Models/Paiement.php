<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Paiement extends Model
{
    protected $fillable = [
        'dette_id',
        'montant',
        // Ajoute ici d'autres champs que tu souhaites autoriser pour l'assignation massive
    ];
}
