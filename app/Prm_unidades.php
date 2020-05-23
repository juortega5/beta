<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Prm_unidades extends Model
{
	/**
     * Relación entre productos y tipo de unidades.
     *
     * @return relacion
     */
    public function productos()
	{
		return $this->hasMany('App\Productos');
	}
}
