<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\Producto;

class DetalleCompra extends Model
{
    protected $table = "detalle_compras";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['cantidad','precio_compra','precio_venta'];
    
	/**
	 * Relación entre compras y detalle compras.
	 *
	 * @return relacion
	 */
    public function compras()
    {
    	return $this->belongsTo('App\Compra','compra_id');
    }

    /**
     * Relación entre productos y detalle compra.
     *
     * @return relacion
     */
    public function producto()
    {
        return $this->belongsTo('App\Producto','producto_id');
    }

    /**
     * Busca los productos asociados a una factura de compra
     *
     * @param  string  $idCompra
     * @return colección con los detalles
     */
    public static function getAllDetalles($idcompra)
    {
        return static::where('compra_id',$idcompra)->with('producto')->get();
    }
   
}
