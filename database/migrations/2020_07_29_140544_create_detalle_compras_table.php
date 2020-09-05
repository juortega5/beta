<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetalleComprasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detalle_compras', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('cantidad');
            $table->integer('precio_compra');
            $table->integer('precio_venta');
            $table->integer('producto_id')->unsigned();
            $table->integer('compra_id')->unsigned();
            $table->timestamps();
            $table->foreign('producto_id')->references('id')->on('productos')
                    ->onUpdate('cascade')
                    ->onDelete('cascade');
            $table->foreign('compra_id')->references('id')->on('compras')
                    ->onUpdate('cascade')
                    ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('detalle_compras');
    }
}
