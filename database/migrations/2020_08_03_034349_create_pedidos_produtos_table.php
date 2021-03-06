<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePedidosProdutosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_pedidos_produtos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('pedido_id')->unsigned();
            $table->foreign('pedido_id')->references('id')->on('tb_pedidos')->onDelete('cascade');
            $table->integer('produto_id')->unsigned();
            $table->foreign('produto_id')->references('id')->on('tb_produtos')->onDelete('cascade');
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
        Schema::dropIfExists('tb_pedidos_produtos');
    }
}
