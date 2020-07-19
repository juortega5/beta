<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Tercero;
use App\RolTercero;
use App\Http\Requests\StoreTercerosRequest;

class TerceroController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) 
        {
            if($request->input('buscar') && $request->input('buscar')<>"null")
            {
                $terceros = Tercero::getAllTercero($request->input('buscar'))->paginate(2);
            }
            else
            {
                $terceros = Tercero::with('roles')->paginate(2);
            }
            $roles = Tercero::getRoles();
            $data = ["terceros"=>$terceros,"listaRoles"=>$roles,"buscar"=>$request->input('buscar')];
            return response()->json($data,200);
        }
        return view('layouts.app');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreTercerosRequest $request)
    {
        if ($request->ajax()) 
        {
            /*Se valida el nombre y el nit en esta sección, pues si se realiza en el StoreTercerosRequest
              no deja pasar los datos cuando se intente editar el registro.*/
            $validateData = $request->validate(['nombre_tercero'=> ['unique:terceros'],'nit'=> ['unique:terceros']]);
            $tercero = new Tercero();
            $tercero->nombre_tercero = $request->input('nombre_tercero');
            $tercero->nit = $request->input('nit');
            $tercero->telefono = $request->input('telefono');
            $tercero->direccion = $request->input('direccion');
            $tercero->slug = Str::of($request->input('nombre_tercero'))->slug('-');
            $tercero->save();
            $terceros = Tercero::with('roles')->where('terceros.slug', $tercero->slug)->first();
            foreach ($request->roles as $rol) 
            {
                $roles = new RolTercero();
                $roles->rol_id = $rol;
                $roles->tercero_id = $terceros->id;
                $roles->save();
            }
            return response()->json(["message"=>"Tercero creado","terceros"=>$terceros],200);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  string  $nit
     * @return \Illuminate\Http\Response
     */
    public function show($nit)
    {
        $terceros = Tercero::getTercero($nit);
        $data = ["terceros"=>$terceros];
        return response()->json($data,200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $tercero
     * @return \Illuminate\Http\Response
     */
    public function update(StoreTercerosRequest $request, Tercero $tercero)
    {
        if ($request->ajax()) 
        {
            $compararSlug = Str::of($request->input('nombre_tercero'))->slug('-');
            $compararNit = $request->input('nit');
            switch (true) 
            {
                //El nombre y nit ya estan en uso.
                case Tercero::where('slug', $compararSlug,'AND')->where('nit', $compararNit,'AND')->where('slug','<>',$tercero->slug)->exists():
                    $validateData = $request->validate(['nombre_tercero'=> ['unique:terceros'],'nit'=> ['unique:terceros']]);
                break;

                //El nit ya esta en uso.
                case Tercero::where('nit', $compararNit,'AND')->where('slug','<>',$tercero->slug)->exists():
                   $validateData = $request->validate(['nit'=> ['unique:terceros']]);
                break;
                
                //El nombre ya esta en uso.
                case Tercero::where('slug', $compararSlug,'AND')->where('slug','<>',$tercero->slug)->exists():
                   $validateData = $request->validate(['nombre_tercero'=> ['unique:terceros']]);
                break;

                default:
                    $tercero->nombre_tercero = $request->input('nombre_tercero');
                    $tercero->nit = $request->input('nit');
                    $tercero->telefono = $request->input('telefono');
                    $tercero->direccion = $request->input('direccion');
                    $tercero->slug = Str::of($request->input('nombre_tercero'))->slug('-');
                    $tercero->save();
                    $terceros = Tercero::with('roles')->where('terceros.slug', $tercero->slug)->first();
                    $deleteRoles = new RolTercero();
                    $deleteRoles::where('tercero_id',$terceros->id)->delete();
                    foreach ($request->roles as $rol) 
                    {
                        $roles = new RolTercero();
                        $roles->rol_id = $rol;
                        $roles->tercero_id = $terceros->id;
                        $roles->save();
                    }
                    return response()->json(["message"=>"Tercero editado","slug"=>$tercero->slug,"terceros"=>$terceros],200);
                break;
            }

        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  string  $tercero
     * @return \Illuminate\Http\Response
     */
    public function destroy(Tercero $tercero)
    {
        $tercero->delete();
        return response()->json(["message"=>"Tercero eliminado"],200);
    }
}
