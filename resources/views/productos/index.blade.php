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
				{!! Form::open(['route'=>['productos.index'],'autocomplete'=>'off', 'class' =>'form-group','method'=>'GET']) !!}
					<div class="input-group mb-3">
						{!! Form::text('buscar',null,['class'=>'form-control','id'=>'c','placeholder'=>'Buscar producto']) !!}
						<div class="input-group-append">
						{!! Form::submit('Buscar',['class'=>'btn btn-dark btn-sm']) !!}
						</div>
					</div>
				{!! Form::close() !!}
				<buscar-component></buscar-component>
			</div>
		</div>
		<tabla-component></tabla-component>
		<modal-component></modal-component>
		<modaldelete-component></modaldelete-component>
	</div>
</div>
@endsection
