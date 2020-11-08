<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFacturasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('facturas', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->bigIncrements('id');

            $table->integer('numero_factura');
            $table->string('IdDocumento')->nullable();
            $table->string('nombre_factura', 128);
            $table->float('total_cost', 8, 2);
            $table->enum('estado', ['pagado','pendiente', 'cancelado', 'validado']);
            $table->date('payment_promise_date')->nullable();
            $table->date('deadline_for_complement')->nullable();

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
        Schema::dropIfExists('facturas');
    }
}
