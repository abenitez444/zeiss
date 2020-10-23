<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->bigIncrements('id');
            $table->string('codigo')->nullable();
            $table->string('mensaje')->nullable();
            $table->string('autorizacion')->nullable();
            $table->string('referencia')->nullable();
            $table->float('importe', 8, 2)->nullable();
            $table->string('mediopago')->nullable();
            $table->string('financiado')->nullable();
            $table->string('plazos')->nullable();
            $table->string('s_transm')->nullable();
            $table->string('hash')->nullable();
            $table->string('tarjetahabiente')->nullable();
            $table->string('cveTipoPago')->nullable();
            $table->string('signature')->nullable();
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
        Schema::dropIfExists('payments');
    }
}
