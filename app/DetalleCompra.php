<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DetalleCompra extends Model
{
    protected $table = "detalle_compras";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['cantidad','precio_compra'];
    
	/**
	 * Relación entre compras y detalle compras.
	 *
	 * @return relacion
	 */
    public function compras()
    {
    	return $this->belongsTo('App\Compra','compra_id');
    }
   
}
