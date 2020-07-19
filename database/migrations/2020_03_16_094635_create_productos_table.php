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
            $table->increments('id');
            $table->string('nombre_producto',100);
            $table->string('codigo',100)->unique();
            $table->string('slug',100)->unique();
            $table->integer('unidad_id')->unsigned();
            $table->integer('precio_venta');
            $table->timestamps();            
            $table->foreign('unidad_id')->references('id')->on('unidades')
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
        Schema::dropIfExists('productos');
    }
}
