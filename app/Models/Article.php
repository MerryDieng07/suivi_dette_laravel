<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    // Définissez les propriétés et relations du modèle ici
    protected $fillable = [
        'libelle',  'prix', 'qteStock', // autres champs
    ];

    /**
     * Scope a query to filter articles by libellé.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $libelle
     * @return \Illuminate\Database\Eloquent\Builder
     */

     protected $hidden = [
        'created_at',
        'updated_at',
    ];
    public function scopeFilterByLibelle($query, $libelle)
    {
        return $query->where('libelle', 'LIKE', '%' . $libelle . '%');
    }

    // Ajoutez ici d'autres scopes ou méthodes que vous avez déjà définis

    /**
     * Un exemple d'autre scope ou méthode que vous pourriez avoir
     */
    public function scopeFilterByQuantite($query, $quantite)
    {
        return $query->where('qteStock', '>=', $quantite);
    }
    
    // Relations avec la table déttes
    public function dettes()
    {
        return $this->belongsToMany(Dette::class, 'article_dette')
                    ->withPivot('quantite', 'prix')
                    ->withTimestamps();
    }
}
