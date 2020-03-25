<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class prm_unidad extends Model
{
    public function productos()
	{
		return $this->hasMany('App\Producto');
	}
}
