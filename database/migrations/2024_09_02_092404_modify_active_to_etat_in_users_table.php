<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyActiveToEtatInUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
{
    Schema::table('users', function (Blueprint $table) {
        if (Schema::hasColumn('users', 'active')) {
            $table->dropColumn('active');
        }
        $table->string('etat')->nullable();
    });
}

public function down()
{
    Schema::table('users', function (Blueprint $table) {
        $table->boolean('active')->default(true);
        $table->dropColumn('etat');
    });
}


}
