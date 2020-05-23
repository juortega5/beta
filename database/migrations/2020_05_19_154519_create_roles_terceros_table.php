<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRolesTercerosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('roles_terceros', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_rol')->unsigned();
            $table->integer('id_tercero')->unsigned();
            $table->foreign('id_rol')->references('id')->on('prm_roles')
                    ->onUpdate('cascade')
                    ->onDelete('cascade');
            $table->foreign('id_tercero')->references('id')->on('terceros')
            ->onUpdate('cascade')
            ->onDelete('cascade');
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
        Schema::dropIfExists('roles_terceros');
    }
}
