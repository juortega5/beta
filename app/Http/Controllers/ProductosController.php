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
            //$productos = Producto::with('tipoUnidad')->get();
           $productos = Producto::with('tipoUnidad')->paginate(5);
            return response()->json($productos);
        }
        if($request->input('buscar'))
        {
            $productos = Producto::buscarProducto($request->input('buscar'))->paginate(5);
        }
        else
        {
            $productos = Producto::paginate(5);
        }
        return view('productos.index',compact('productos'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $select = Producto::selectUnidades();
        return view('productos.create',compact('select'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreProductosRequest $request)
    {
        $validateData = $request->validate(['nombre_producto'=> 'unique:productos']);
        $producto = new Producto();
        $producto->nombre_producto = $request->input('nombre_producto');
        $producto->slug = Str::of($request->input('nombre_producto'))->slug('-');
        $producto->id_unidades = $request->input('id_unidades');
        $producto->precio_venta = $request->input('precio_venta');
        $producto->save();
        return redirect()->route('productos.create')->with('status','Nuevo producto creado');
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
     * @param  string  $producto
     * @return \Illuminate\Http\Response
     */
    public function edit(Producto $producto)
    {
        $select = Producto::selectUnidades();
        return view('productos.edit',compact('producto','select'));
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
        $comparar = Str::of($request->input('nombre_producto'))->slug('-');
        if ($comparar == $producto->slug || !Producto::where('slug', $comparar)->exists()) 
        {
            $producto->slug = $comparar;
            $producto->fill($request->all());
            $producto->save();
            return redirect()->route('productos.index')->with('status','Producto actualizado');
        }
        else
        {
            $validateData = $request->validate(['nombre_producto'=> 'unique:productos']);
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
        return redirect()->route('productos.index')->with('status','Producto eliminado');;
    }
}
