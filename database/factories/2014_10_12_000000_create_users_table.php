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
    {
        Schema::create('users', function (Blueprint $table) {
            $table->boolean('active')->default(true);
            $table->id();
            $table->string('nom');
            $table->string('prenom');
            $table->string('login')->unique();
            $table->string('password');
            $table->foreignId('roleId')->constraint('roles')->ondelete('cascade');
            $table->string('photo')->nullable();
            $table->enum('active', ['actif', 'inactif'])->default('inactif');
            
        
            $table->timestamps();
        });
    }

    

    /**
     * Reverse the migrations.
     */
    public function down()
{
    Schema::table('users', function (Blueprint $table) {
        $table->dropColumn('active');
        // $table->dropColumn(['name', 'email', 'password']);
    });
}
};

