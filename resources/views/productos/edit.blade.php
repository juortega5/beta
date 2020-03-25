@extends('layouts.app')

@section('title','Editar Producto')

@section('route')
<li class="breadcrumb-item active" aria-current="page"><a href="{{route('productos.index') }}">Productos</a></li>
<li class="breadcrumb-item" aria-current="page">Editar Producto</li>
@endsection

@section('content')
<hr>
<div class="row">
	<div class="col-md-4">
		@include('common.errors')
	</div>
	<div class="col-md-4 border border-secondary rounded">
		{!! Form::model( $producto,['route' => ['productos.update',$producto], 'method'=> 'PUT', 'autocomplete'=>'off']) !!}
			@include('productos.form',['nombreBoton'=>'Actualizar producto'])
		{!! Form::close() !!}
	</div>
</div>
@endsection
