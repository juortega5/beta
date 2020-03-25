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
		@if($productos->isEmpty())
		Listado de productos<a class="nav-link" href="{{route('productos.create') }}">Nuevo</a>
		@else
			<div class="row">
				<div class="col-md-4"></div>
				<div class="col-md-4">
					{!! Form::open(['route'=>['productos.index'],'autocomplete'=>'off', 'class' =>'form-group','method'=>'GET']) !!}
						<div class="input-group mb-3">
							{!! Form::text('buscar',null,['class'=>'form-control','id'=>'buscar','placeholder'=>'Buscar producto']) !!}
							<div class="input-group-append">
							{!! Form::submit('Buscar',['class'=>'btn btn-dark btn-sm']) !!}
							</div>
						</div>
					{!! Form::close() !!}
				</div>
			</div>
			<table class="table table-dark table-hover table-bordered table-sm">
				<thead>
					<tr align="center">
						<th colspan="4">Listado de Productos</th>
						<th><a class="btn btn-success btn-sm" href="{{route('productos.create') }}">Nuevo+</a></th>
					</tr>
				    <tr align="center">
				      <th scope="col">Nombre del producto</th>
				      <th scope="col">Unidad de medida</th>
				      <th scope="col">Precio por unidad</th>
				      <th scope="col">Editar</th>
				      <th scope="col">Eliminar</th>
				    </tr>
				</thead>
	  			<tbody>
				@foreach($productos as $producto)
				    <tr>
				      <td>{{ $producto->nombre_producto}}</td>
				      <td align="center">{{ $producto->tipoUnidad->unidad}}</td>
				      <td align="center">{{ $producto->precio_venta}}</td>
				      <td align="center"><a class="btn btn-primary btn-sm" href="productos/{{$producto->slug}}/edit">Editar</a></td>
				      <td align="center">
				      	{!! Form::open(['route'=>['productos.destroy',$producto->slug],'method'=>'DELETE']) !!}
				      		{!! Form::submit('Eliminar',['class'=>'btn btn-danger btn-sm']) !!}
				      	{!! Form::close() !!}
				      </td>
				    </tr>
				@endforeach
				</tbody>
			</table>
			{{ $productos->links() }}
			@include('common.success')
		@endif
	</div>
</div>
@endsection
