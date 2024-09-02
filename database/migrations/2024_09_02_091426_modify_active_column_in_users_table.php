<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyActiveColumnInUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // Supprimer la colonne `active` si elle existe déjà
            $table->dropColumn('active');
            
            // Ajouter la colonne `active` avec les valeurs ENUM
            $table->enum('active', ['actif', 'inactif'])->default('inactif');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            // Revenir à la colonne `active` de type integer ou autre
            $table->dropColumn('active');

            // Remettre l'ancienne colonne si nécessaire
            $table->boolean('active')->default(0);
        });
    }
}
