<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\Unidad;
use App\Categoria;

class Producto extends Model
{

    protected $table = "productos";
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
	protected $fillable = ['nombre_producto','codigo','unidad_id','slug','precio_venta'];
    
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
    public function unidad()
    {
    	return $this->belongsTo('App\Unidad','unidad_id');
    }

     /**
     * Relación entre productos y categorias.
     *
     * @return relacion
     */
    public function categoria()
    {
        return $this->belongsTo('App\Categoria','categoria_id');
    }

    /**
     * Crea un arreglo con los tipos de unidades.
     *
     * @return array
     */
    public static function getUnidades()
    {
    	$objUnidades = Unidad::all();
        $arreglo = [];
        foreach($objUnidades as $objUnidades)
        {
            $arreglo[$objUnidades->id] = $objUnidades->unidad;
        }
        return $arreglo;
    }

     /**
     * Crea un arreglo con los tipos de categorías.
     *
     * @return array
     */
    public static function getCategorias()
    {
        $objCategorias = Categoria::all();
        $arreglo = [];
        foreach($objCategorias as $objCategorias)
        {
            $arreglo[$objCategorias->id] = $objCategorias->categoria;
        }
        return $arreglo;
    }

    /**
     * Busca coincidencias de productos con el string ingresado.
     *
     * @param  string  $producto
     * @return colección con las coincidencias
     */
    public static function getAllProductos($producto)
    {
        return static::where('nombre_producto','LIKE',"%$producto%")->with('unidad')->with('categoria');
    }

    /**
     * Busca los datos del producto con el codigo ingresado.
     *
     * @param  string  $codigo
     * @return colección con los resultados
    */
    public static function getProducto($codigo)
    {
        return static::where('codigo',$codigo)->first();
    }
}
