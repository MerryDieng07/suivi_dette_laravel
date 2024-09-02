<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Enums\EtatEnum; 

class Client extends Model
{
    
    use HasFactory;

   // public mixed $user_id;
    protected $fillable = [
        'surname',
        'adresse',
        'telephone',
        'user_id',
    ];
    protected $hidden = [
        //  'password',
        'created_at',
        'updated_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
