<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\DetalleCompra;
use App\Producto;

class DetalleCompraController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
    public function store(Request $request)
    {
        if ($request->ajax()) 
        {
            $detalle = new DetalleCompra();
            $detalle->cantidad = $request->input('cantidad');
            $detalle->precio_compra = $request->input('precio');
            $detalle->precio_venta = $request->input('precio_venta');
            $detalle->producto_id = $request->input('producto_id');
            $detalle->compra_id = $request->input('compra_id');
            $detalle->save();
            $producto = new Producto();
            $producto->where('id', $request->input('producto_id'))->update(['precio_venta' => $request->input('precio_venta')]);
            $articulos = DetalleCompra::getAllDetalles($request->input('compra_id'));
            return response()->json(["message"=>"Articulo cargado","articulos"=>$articulos],200);
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
        $articulos = DetalleCompra::getAllDetalles($id);
        return response()->json(["articulos"=>$articulos],200);
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $detalle = new DetalleCompra();
        $articulo = $detalle->find($id);
        $articulo->delete();
        $articulos = DetalleCompra::getAllDetalles($articulo->compra_id);
        return response()->json(["message"=>"Articulo descartado","articulos"=>$articulos],200);
    }
}
