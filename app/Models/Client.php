<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Enums\EtatEnum; 

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'surname',
        'adresse',
        'telephone',
        'user_id',
    ];
    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope pour filtrer les clients par numéro de téléphone.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $telephone
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeClientByTelephone($query, $telephone)
    {
        
        return $query->where('telephone', $telephone);
    }

    
    
}
