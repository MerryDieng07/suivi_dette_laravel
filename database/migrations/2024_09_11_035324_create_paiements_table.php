<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {Schema::create('paiements', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('dette_id');
        $table->decimal('montant', 10, 2);
        $table->timestamps();
    
        // Clé étrangère vers la table dettes
        $table->foreign('dette_id')->references('id')->on('dettes')->onDelete('cascade');
    });
    
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('paiements');
    }
};
