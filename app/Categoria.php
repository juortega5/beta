<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    protected $table = "categorias";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['categoria','slug'];

	/**
     * Relación entre productos y categorias.
     *
     * @return relacion
     */
    public function productos()
	{
		return $this->hasMany('App\Producto');
	}
}
