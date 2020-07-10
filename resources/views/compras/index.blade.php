@extends('layouts.app')

@section('title','Compras')

@section('route')
<li class="breadcrumb-item active" aria-current="page">Compras</li>
@endsection

@section('content')
<hr>
<div class="row">
	<div class="col-md-12">
		<div class="row">
			<div class="col-md-4"></div>
			<div class="col-md-4">
				<buscarcompras-component></buscarcompras-component>
			</div>
		</div>
		<tablacompras-component></tablacompras-component>
		<modalcompras-component></modalcompras-component>
		<modaldeletecompras-component></modaldeletecompras-component>
	</div>
</div>
@endsection