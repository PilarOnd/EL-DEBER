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
        Schema::create('creatividades', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->enum('formato',['desktop', 'mobile' ]);
            $table->enum('espacio_cpm',['premium', 'gold', 'social']);
            $table->foreignId('anunciante')->constrained('empresas');
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
        Schema::dropIfExists('creatividades');
    }
};
