<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_clientes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nome', 200);
            $table->string('email', 100)->unique();
            $table->string('telefone', 25)->unique();
            $table->string('endereco', 500);
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
        Schema::dropIfExists('tb_clientes');
    }
}
