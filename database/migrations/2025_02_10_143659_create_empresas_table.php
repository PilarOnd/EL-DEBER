<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
{
    Schema::create('empresas', function (Blueprint $table) {
        $table->id();
        $table->string('nombre');
        $table->enum('tipo', ['agencia', 'redes de publicidad', 'anunciante', 'anunciante interno']);
        $table->timestamps();
    });
}

    public function down()
{
    Schema::dropIfExists('empresas');
}

};
