<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\prm_roles;

class Terceros extends Model
{
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
        return $this->belongsToMany('App\Prm_roles','roles_terceros','id_tercero','id_rol');
    }

    /**
     * Crea un arreglo con los tipos de roles.
     *
     * @return array
     */
    public static function listaRoles()
    {
    	$prm_roles = prm_roles::all();
        $lista = [];
        foreach($prm_roles as $prm_roles)
        {
            $lista[$prm_roles->id] = $prm_roles->rol;
        }
        return $lista;
    }

    /**
     * Busca coincidencias de terceros con el string ingresado.
     *
     * @param  string  $tercero
     * @return colección con las coincidencias
     */
    public static function buscarTercero($tercero)
    {
        return static::where('nombre_tercero','LIKE',"%$tercero%")->with('roles');
    }


    /**
     * Busca los datos del tercero con el nit ingresado.
     *
     * @param  string  $nit
     * @return colección con los resultados
     */
    public static function obtenerTercero($nit)
    {
        return static::where('nit',$nit)->first();
    }
}
