<?php

use App\Enums\RoleEnum;
use App\Enums\EtatEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->string('prenom');
            $table->string('login')->unique();
            $table->foreignId('role_id')->constrained('roles')->onDelete('cascade');
            $table->string('password');
            $table->enum('etat', array_column(EtatEnum::cases(), 'value'))->default(EtatEnum::ACTIF->value);
            $table->string('photo')->nullable(); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};