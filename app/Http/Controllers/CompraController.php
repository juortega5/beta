<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Compra;
use App\DetalleCompra;
use App\Http\Requests\StoreComprasRequest;

class CompraController extends Controller
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
            if($request->input('buscar') && $request->input('buscar') <> "null")
            {
                $compras = Compra::getAllFactura($request->input('buscar'))->paginate(2);
            }
            else
            {
                $compras = Compra::with('tercero')->paginate(2);
            }
            $data = ["compras"=>$compras,"buscar"=>$request->input('buscar')];
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
    public function store(StoreComprasRequest $request)
    {
        if ($request->ajax()) 
        {
            $compra = new Compra();
            $compra->numero = $request->input('numero');
            $compra->fecha = $request->input('fecha');
            $compra->tercero_id = $request->input('tercero_id');
            $compra->save();
            $compras = Compra::with('tercero')->get()->last();
            foreach ($request->productos as $producto) 
            {
                $productos = new DetalleCompra();
                $productos->cantidad = $producto['cantidad'];
                $productos->precio_compra = $producto['precio'];
                $productos->producto_id = $producto['id'];
                $productos->compra_id = $compras->id;
                $productos->save();
            }
            return response()->json(["message"=>"compra creada","compras"=>$compras],200);
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
        Compra::destroy($id);
        return response()->json(["message"=>"Factura eliminada"],200);
    }
}
