<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\Rol;

class Tercero extends Model
{

    protected $table = "terceros";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['nombre_tercero','nit','telefono','direccion','roles'];

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
     * Relación entre terceros,roles y la tabla pivot roles_terceros.
     *
     * @return relacion
     */
    public function roles()
    {
        return $this->belongsToMany('App\Rol','rol_tercero','tercero_id','rol_id');
    }

    /**
     * Relación entre compras y terceros.
     *
     * @return relacion
     */
    public function compras()
    {
        return $this->hasMany('App\Compra');
    }

    /**
     * Crea un arreglo con los tipos de roles.
     *
     * @return array
     */
    public static function getRoles()
    {
    	$objRol = Rol::all();
        $arreglo = [];
        foreach($objRol as $objRol)
        {
            $arreglo[$objRol->id] = $objRol->rol;
        }
        return $arreglo;
    }

    /**
     * Busca coincidencias de terceros con el nombre y rol ingresado.
     *
     * @param  string  $tercero
     * @param  string  $rol
     * @return colección con las coincidencias
     */
    public static function getAllTercero($tercero,$rol=Null)
    {
        $coincidenciasTerceros = static::where('nombre_tercero','LIKE',"%$tercero%")->with('roles');
        if ($rol <> Null) 
        {
            $coincidenciasTerceros->whereHas('roles',function($query) use ($rol) {  $query->where('rol',$rol); });
        }
        return $coincidenciasTerceros;              
    }


    /**
     * Busca los datos del tercero con el nombre ingresado.
     *
     * @param  string  $tercero
     * @return colección con los resultados
     */
    public static function getTercero($tercero)
    {
        return static::where('nombre_tercero',$tercero)->with('roles')->first();
    }
}
