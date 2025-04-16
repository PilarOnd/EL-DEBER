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
        Schema::create('pedidos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->foreignId('linea_pedido_id')->constrained('linea_pedidos');
            $table->foreignId('anunciante')->constrained('empresas');
            $table->string('segmento');
            $table->timestamp('fecha_inicio')->nullable();
            $table->timestamp('fecha_finalizacion')->nullable();
            $table->decimal('presupuesto', 8, 2);
            $table->integer('objetivo_proyectado');
            $table->integer('objetivo_alcanzado');
            $table->decimal('porcentaje_efectividad', 5, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pedidos');
    }
    
};
