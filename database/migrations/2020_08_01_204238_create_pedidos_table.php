<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePedidosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_pedidos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('cliente_id')->unsigned();
            $table->foreign('cliente_id')->references('id')->on('tb_clientes');
            $table->integer('status_id')->unsigned();
            $table->foreign('status_id')->references('id')->on('tb_status');
            $table->timestamps();
        });

        Schema::create('tb_pedidos_produtos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('pedido_id')->unsigned();
            $table->foreign('pedido_id')->references('id')->on('tb_pedidos');
            $table->integer('produto_id')->unsigned();
            $table->foreign('produto_id')->references('id')->on('tb_produtos');
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
        Schema::dropIfExists('tb_pedidos');
        Schema::dropIfExists('tb_pedidos_produtos');
    }
}
