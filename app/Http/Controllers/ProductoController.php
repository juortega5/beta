<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Producto;
use App\Http\Requests\StoreProductosRequest;

class ProductoController extends Controller
{
    /**
     * Display a listing of the resource.
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) 
        {
            if($request->input('buscar') && $request->input('buscar')<>"null")
            {
                $productos = Producto::getAllProductos($request->input('buscar'))->paginate(2);
            }
            else
            {
                $productos = Producto::with('unidad')->paginate(2);
            }
            $unidades = Producto::getUnidades();
            $data = ["productos"=>$productos,"unidades"=> $unidades,"buscar"=>$request->input('buscar')];
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
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreProductosRequest $request)
    {
        if ($request->ajax()) 
        {
            /*Se valida el nombre y el codigo en esta sección, pues si se realiza en el StoreProductosRequest
              no deja pasar los datos cuando se intente editar el registro.*/
            $validateData = $request->validate(['nombre_producto'=> ['unique:productos'], 'codigo'=> ['unique:productos']]);
            $producto = new Producto();
            $producto->nombre_producto = $request->input('nombre_producto');
            $producto->codigo = $request->input('codigo');
            $producto->slug = Str::of($request->input('nombre_producto'))->slug('-');
            $producto->unidad_id = $request->input('unidad_id');
            $producto->precio_venta = $request->input('precio_venta');
            $producto->save();
            $productos = Producto::with('unidad')->where('productos.slug', $producto->slug)->first();
            return response()->json(["message"=>"Producto creado","productos"=>$productos],200);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  string  $codigo
     * @return \Illuminate\Http\Response
     */
    public function show($codigo)
    {
        $producto = Producto::getProducto($codigo);
        $data = ["productos"=>$producto];
        return response()->json($data,200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  string  $producto
     * @return \Illuminate\Http\Response
     */
    public function edit(Producto $producto)
    {
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $producto
     * @return \Illuminate\Http\Response
     */
    public function update(StoreProductosRequest $request, Producto $producto)
    {
        if ($request->ajax()) 
        {
            $compararSlug = Str::of($request->input('nombre_producto'))->slug('-');
            $compararCodigo = $request->input('codigo');
            switch (true) 
            {    
                //El nombre y codigo ya estan en uso.
                case Producto::where('slug', $compararSlug,'AND')->where('codigo', $compararCodigo,'AND')->where('slug','<>',$producto->slug)->exists():
                   $validateData = $request->validate(['nombre_producto'=> ['unique:productos'],'codigo'=> ['unique:productos']]);
                break;

                //El codigo ya esta en uso.
                case Producto::where('codigo', $compararCodigo,'AND')->where('slug','<>',$producto->slug)->exists():
                   $validateData = $request->validate(['codigo'=> ['unique:productos']]);
                break;

                //El nombre ya esta en uso.
                case Producto::where('slug', $compararSlug,'AND')->where('slug','<>',$producto->slug)->exists():
                   $validateData = $request->validate(['nombre_producto'=> ['unique:productos']]);
                break;
                
                default:
                    $producto->slug = $compararSlug;
                    $producto->nombre_producto = $request->input('nombre_producto');
                    $producto->codigo = $request->input('codigo');
                    $producto->unidad_id = $request->input('unidad_id');
                    $producto->precio_venta = $request->input('precio_venta');
                    $producto->save();
                    $productos = Producto::with('unidad')->where('productos.slug', $producto->slug)->first();
                    return response()->json(["message"=>"Producto editado","slug"=>$producto->slug,"productos"=>$productos],200);
                break;
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  string  $producto
     * @return \Illuminate\Http\Response
     */
    public function destroy(Producto $producto)
    {
        $producto->delete();
        return response()->json(["message"=>"Producto eliminado"],200);
    }
}
