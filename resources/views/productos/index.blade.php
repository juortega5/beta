@extends('layouts.app')

@section('title','Productos')

@section('route')
<li class="breadcrumb-item active" aria-current="page">Productos</li>
@endsection

@section('content')
<hr>
<div class="row">
	<div class="col-md-1"></div>
	<div class="col-md-10">
		<div class="row">
			<div class="col-md-4"></div>
			<div class="col-md-4">
				<buscarproducto-component></buscarproducto-component>
			</div>
		</div>
		<tablaproducto-component></tablaproducto-component>
		<modalproducto-component></modalproducto-component>
		<modaldeleteproducto-component></modaldeleteproducto-component>
	</div>
</div>
@endsection
