<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Producto;
use App\Http\Requests\StoreProductosRequest;

class ProductosController extends Controller
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
                $productos = Producto::buscarProducto($request->input('buscar'))->paginate(2);
            }
            else
            {
                $productos = Producto::with('tipoUnidad')->paginate(2);
            }
            $selectUnidades = Producto::selectUnidades();
            $data = ["productos"=>$productos,"unidades"=> $selectUnidades,"buscar"=>$request->input('buscar')];
            return response()->json($data,200);
        }
        return view('productos.index');
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
            $validateData = $request->validate(['nombre_producto'=> 'unique:productos']);
            $producto = new Producto();
            $producto->nombre_producto = $request->input('nombre_producto');
            $producto->slug = Str::of($request->input('nombre_producto'))->slug('-');
            $producto->id_unidades = $request->input('id_unidades');
            $producto->precio_venta = $request->input('precio_venta');
            $producto->save();
            $productos = Producto::with('tipoUnidad')->where('productos.slug', $producto->slug)->first();
            return response()->json(["message"=>"Producto creado","productos"=>$productos],200);
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
            $comparar = Str::of($request->input('nombre_producto'))->slug('-');
            if ($comparar == $producto->slug || !Producto::where('slug', $comparar)->exists()) 
            {
                $producto->slug = $comparar;
                $producto->nombre_producto = $request->input('nombre_producto');
                $producto->id_unidades = $request->input('id_unidades');
                $producto->precio_venta = $request->input('precio_venta');
                $producto->save();
                $productos = Producto::with('tipoUnidad')->where('productos.slug', $producto->slug)->first();
                return response()->json(["message"=>"Producto editado","slug"=>$producto->slug,"productos"=>$productos],200);
            }
            else
            {
                $validateData = $request->validate(['nombre_producto'=> 'unique:productos']);
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
