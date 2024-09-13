<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dette extends Model
{
    use HasFactory;

    protected $fillable = ['montant', 'montant_paye', 'client_id'];


    // Relation many-to-many avec Article
    public function articles()
    {
        return $this->belongsToMany(Article::class, 'article_dette')
                    ->withPivot('quantite', 'prix')
                    ->withTimestamps();
    }

    // Relation avec Client
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    // Relation avec Paiement
    public function paiements()
    {
        return $this->hasMany(Paiement::class);
    }

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    protected $appends = ['montant_du', 'montant_restant'];

    // Attribut calculé pour le montant dû
    public function getMontantDuAttribute()
    {
        // Calculer la somme des paiements effectués pour cette dette
        $totalPaye = $this->paiements->sum('montant');

        // Retourner le montant total de la dette moins le montant payé
        return $this->montant_total - $totalPaye;
    }

    // Attribut calculé pour le montant restant à payer
    public function getMontantRestantAttribute()
    {
        // Calculer la somme des paiements effectués pour cette dette
        $totalPaye = $this->paiements->sum('montant');

        // Retourner le montant total de la dette moins le montant payé
        return $this->montant_total - $totalPaye;
    }

    // Scope pour les dettes soldées
    public function scopeSolde($query)
    {
        return $query->whereRaw('montant - montant_paye = 0');
    }

    // Scope pour les dettes non soldées
    public function scopeNonSolde($query)
    {
        return $query->whereRaw('montant - montant_paye != 0');
    }

   
}
