@extends('layouts.app')

@section('title','Nuevo Producto')

@section('route')
<li class="breadcrumb-item active" aria-current="page"><a href="{{route('productos.index') }}">Productos</a></li>
<li class="breadcrumb-item" aria-current="page">Nuevo Producto</li>
@endsection

@section('content')
<hr>
<div class="row">
	<div class="col-md-4">
		@include('common.errors')
	</div>
	<div class="col-md-4 border border-secondary rounded">
		{!! Form::open(['route' => 'productos.store', 'method'=> 'POST', 'autocomplete'=>'off', 'class' =>'form-group']) !!}
 			@include('productos.form',['nombreBoton'=>'Guardar producto'])
		{!! Form::close() !!}
		@include('common.success')
	</div>
</div>
@endsection
