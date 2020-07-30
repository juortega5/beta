<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Unidad extends Model
{
	protected $table = "unidades";
    
	/**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['unidad','slug'];

	/**
     * Relación entre productos y tipo de unidades.
     *
     * @return relacion
     */
    public function productos()
	{
		return $this->hasMany('App\Producto');
	}
}
