<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Terceros;
use App\Roles_terceros;
use App\Http\Requests\StoreTercerosRequest;

class TercerosController extends Controller
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
                $terceros = Terceros::buscarTercero($request->input('buscar'))->paginate(2);
            }
            else
            {
                $terceros = Terceros::with('roles')->paginate(2);
            }
            $listaRoles = Terceros::listaRoles();
            $data = ["terceros"=>$terceros,"listaRoles"=>$listaRoles,"buscar"=>$request->input('buscar')];
            return response()->json($data,200);
        }
        return view('terceros.index');
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
            $validateData = $request->validate(['nombre_tercero'=> ['unique:terceros'],'nit'=> ['unique:terceros']]);
            $tercero = new Terceros();
            $tercero->nombre_tercero = $request->input('nombre_tercero');
            $tercero->nit = $request->input('nit');
            $tercero->telefono = $request->input('telefono');
            $tercero->direccion = $request->input('direccion');
            $tercero->slug = Str::of($request->input('nombre_tercero'))->slug('-');
            $tercero->save();
            $terceros = Terceros::with('roles')->where('terceros.slug', $tercero->slug)->first();
            foreach ($request->roles as $rol) 
            {
                $roles = new Roles_terceros();
                $roles->id_rol = $rol;
                $roles->id_tercero = $terceros->id;
                $roles->save();
            }
            return response()->json(["message"=>"Tercero creado","terceros"=>$terceros],200);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
    public function update(StoreTercerosRequest $request, Terceros $tercero)
    {
        if ($request->ajax()) 
        {
            $comparar = Str::of($request->input('nombre_tercero'))->slug('-');
            $comparaNit = $request->input('nit');
            if ( Terceros::where('nit', $comparaNit,'AND')->where('slug', $tercero->slug)->exists() && $comparar == $tercero->slug || 
                $comparar == $tercero->slug && !Terceros::where('nit', $comparaNit)->exists() || 
                !Terceros::where('slug', $comparar)->exists() && Terceros::where('nit', $comparaNit,'AND')->where('slug', $tercero->slug)->exists() || 
                !Terceros::where('slug', $comparar)->exists() && !Terceros::where('nit', $comparaNit)->exists() ) 
            { 
                $tercero->nombre_tercero = $request->input('nombre_tercero');
                $tercero->nit = $request->input('nit');
                $tercero->telefono = $request->input('telefono');
                $tercero->direccion = $request->input('direccion');
                $tercero->slug = Str::of($request->input('nombre_tercero'))->slug('-');
                $tercero->save();
                $terceros = Terceros::with('roles')->where('terceros.slug', $tercero->slug)->first();
                $deleteRoles = new Roles_terceros();
                $deleteRoles::where('id_tercero',$terceros->id)->delete();
                foreach ($request->roles as $rol) 
                {
                    $roles = new Roles_terceros();
                    $roles->id_rol = $rol;
                    $roles->id_tercero = $terceros->id;
                    $roles->save();
                }
                return response()->json(["message"=>"Tercero editado","slug"=>$tercero->slug,"terceros"=>$terceros],200);
            }
            else
            {
                switch (true) {
                    case Terceros::where('nit', $comparaNit)->exists() && $comparar == $tercero->slug:
                       $validateData = $request->validate(['nit'=> ['unique:terceros']]);
                    break;

                    case Terceros::where('slug',  $comparar)->exists() && Terceros::where('nit', $comparaNit,'AND')->where('slug', $tercero->slug)->exists():
                       $validateData = $request->validate(['nombre_tercero'=> ['unique:terceros']]);
                    break;
                    
                    default:
                       $validateData = $request->validate(['nombre_tercero'=> ['unique:terceros'],'nit'=> ['unique:terceros']]);
                    break;
                }
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  string  $tercero
     * @return \Illuminate\Http\Response
     */
    public function destroy(Terceros $tercero)
    {
        $tercero->delete();
        return response()->json(["message"=>"Tercero eliminado"],200);
    }
}
