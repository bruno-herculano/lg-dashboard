<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProdutividadesTable extends Migration
{

    public function up()
    {
        Schema::create('produtividades', function (Blueprint $table) {
            $table->id();
            $table->string('linha_produto');           // Geladeira, Máquina de Lavar, TV, Ar-Condicionado
            $table->string('planta')->default('Planta A');
            $table->date('data_producao');             // Data do registro de produção
            $table->integer('quantidade_produzida');   // Total produzido no dia
            $table->integer('quantidade_defeitos');    // Total com defeito no dia
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('produtividades');
    }
}
