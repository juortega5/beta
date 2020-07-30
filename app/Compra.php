<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Compra extends Model
{
    protected $table = "compras";
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['numero','fecha','tercero_id'];

    /**
     * Relación entre compras y detalle compras.
     *
     * @return relacion
     */
    public function detalleCompra()
	{
		return $this->hasMany('App\DetalleCompra');
	}

    /**
     * Relación entre compras y terceros.
     *
     * @return relacion
     */
    public function tercero()
    {
        return $this->belongsTo('App\Tercero','tercero_id');
    }

    /**
     * Busca coincidencias de facturas con el string ingresado.
     *
     * @param  string  $factura
     * @return colección con las coincidencias
     */
    public static function getAllFactura($factura)
    {
        return static::where('numero','LIKE',"%$factura%")->with('tercero');
    }
}
