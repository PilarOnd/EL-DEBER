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
    Schema::create('linea_pedidos', function (Blueprint $table) {
        $table->id();
        $table->string('nombre');
        $table->enum('estado', ['detenido', 'finalizado', 'publicando', 'listo']);
        $table->enum('tipo', ['patrocinio', 'estandar', 'propia']);
        $table->timestamp('fecha_inicio')->nullable();
        $table->timestamp('fecha_finalizacion')->nullable();
        $table->integer('objetivo'); 
        $table->foreignId('empresa_id')->constrained('empresas');
        $table->timestamps();
    });
}

public function down()
{
    Schema::dropIfExists('linea_pedidos');
}

};
