<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFotoFechaFotoCodigoMedidorSubidaToMediciones extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('mediciones', function (Blueprint $table) {
            $table->string('codigo_medidor')->nullable();  // Nueva columna para el código del medidor
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('mediciones', function (Blueprint $table) {
            $table->dropColumn(['codigo_medidor']);
        });
    }
}
