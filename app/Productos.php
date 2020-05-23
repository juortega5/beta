<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\Prm_unidades;

class Productos extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
	protected $fillable = ['nombre_producto','id_unidades','slug','precio_venta'];

    /**
     * Get the route key for the model.
     *
     * @return string
     */
	public function getRouteKeyName()
	{
	    return 'slug';
	} 

    /**
     * Relación entre productos y tipo de unidades.
     *
     * @return relacion
     */
    public function tipoUnidad()
    {
    	return $this->belongsTo('App\Prm_unidades','id_unidades');
    }

    /**
     * Crea un arreglo con los tipos de unidades.
     *
     * @return array
     */
    public static function selectUnidades()
    {
    	$prm_unidades = Prm_unidades::all();
        $select = [];
        foreach($prm_unidades as $prm_unidades)
        {
            $select[$prm_unidades->id] = $prm_unidades->unidad;
        }
        return $select;
    }

    /**
     * Busca coincidencias de productos con el string ingresado.
     *
     * @param  string  $producto
     * @return colección con las coincidencias
     */
    public static function buscarProducto($producto)
    {
        return static::where('nombre_producto','LIKE',"%$producto%")->with('tipoUnidad');
    }
}
