<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('productos', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->bigIncrements('id');

            //unsigned() no permite num negativos
            $table->BigInteger('categorias_id')->unsigned();
            $table->BigInteger('puntos_id')->unsigned();

            $table->string('codigo', 128)->nullable();
            $table->string('nombre', 128);
            $table->integer('stock');
            $table->text('descripcion');
            $table->string('imagen', 128)->nullable();
            //$table->boolean('estado');
            $table->enum('estado', ['activo', 'inactivo']);

            $table->timestamps();


            //Relations
            $table->foreign('categorias_id')->references('id')->on('categorias')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('puntos_id')->references('id')->on('puntos')
                ->onDelete('cascade')
                ->onUpdate('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('productos');
    }
}
