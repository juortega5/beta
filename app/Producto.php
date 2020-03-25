<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\prm_unidad;

class Producto extends Model
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

    public function tipoUnidad()
    {
    	return $this->belongsTo('App\prm_unidad','id_unidades');
    }

    public static function selectUnidades()
    {
    	$prm_unidades = prm_unidad::all();
        $select = [];
        foreach($prm_unidades as $prm_unidades)
        {
            $select[$prm_unidades->id] = $prm_unidades->unidad;
        }
        return $select;
    }

    public static function buscarProducto($producto)
    {
        return static::where('nombre_producto','LIKE',"%$producto%");
    }
}
