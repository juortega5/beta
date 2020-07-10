<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Productos;
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
                $productos = Productos::buscarProducto($request->input('buscar'))->paginate(2);
            }
            else
            {
                $productos = Productos::with('tipoUnidad')->paginate(2);
            }
            $selectUnidades = Productos::selectUnidades();
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
            $validateData = $request->validate(['nombre_producto'=> ['unique:productos'], 'codigo'=> ['unique:productos']]);
            $producto = new Productos();
            $producto->nombre_producto = $request->input('nombre_producto');
            $producto->codigo = $request->input('codigo');
            $producto->slug = Str::of($request->input('nombre_producto'))->slug('-');
            $producto->id_unidades = $request->input('id_unidades');
            $producto->precio_venta = $request->input('precio_venta');
            $producto->save();
            $productos = Productos::with('tipoUnidad')->where('productos.slug', $producto->slug)->first();
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
        $productos = Productos::obtenerProducto($codigo);
        $data = ["productos"=>$productos];
        return response()->json($data,200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  string  $producto
     * @return \Illuminate\Http\Response
     */
    public function edit(Productos $producto)
    {
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $producto
     * @return \Illuminate\Http\Response
     */
    public function update(StoreProductosRequest $request, Productos $producto)
    {
        if ($request->ajax()) 
        {
            $comparar = Str::of($request->input('nombre_producto'))->slug('-');
            $comparaCodigo = $request->input('codigo');
            if ( Productos::where('codigo', $comparaCodigo,'AND')->where('slug', $producto->slug)->exists() && $comparar == $producto->slug || 
                $comparar == $producto->slug && !Productos::where('codigo', $comparaCodigo)->exists() || 
                !Productos::where('slug', $comparar)->exists() && Productos::where('codigo', $comparaCodigo,'AND')->where('slug', $producto->slug)->exists() || 
                !Productos::where('slug', $comparar)->exists() && !Productos::where('codigo', $comparaCodigo)->exists() ) 
            {
                $producto->slug = $comparar;
                $producto->nombre_producto = $request->input('nombre_producto');
                $producto->codigo = $request->input('codigo');
                $producto->id_unidades = $request->input('id_unidades');
                $producto->precio_venta = $request->input('precio_venta');
                $producto->save();
                $productos = Productos::with('tipoUnidad')->where('productos.slug', $producto->slug)->first();
                return response()->json(["message"=>"Producto editado","slug"=>$producto->slug,"productos"=>$productos],200);
            }
            else
            {
                switch (true) {
                    case Productos::where('codigo', $comparaCodigo)->exists() && $comparar == $producto->slug:
                       $validateData = $request->validate(['codigo'=> ['unique:productos']]);
                    break;

                    case Productos::where('slug',  $comparar)->exists() && Productos::where('codigo', $comparaCodigo,'AND')->where('slug', $producto->slug)->exists():
                       $validateData = $request->validate(['nombre_producto'=> ['unique:productos']]);
                    break;
                    
                    default:
                       $validateData = $request->validate(['nombre_producto'=> ['unique:productos'],'codigo'=> ['unique:productos']]);
                    break;
                }
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  string  $producto
     * @return \Illuminate\Http\Response
     */
    public function destroy(Productos $producto)
    {
        $producto->delete();
        return response()->json(["message"=>"Producto eliminado"],200);
    }
}
