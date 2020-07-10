@extends('layouts.app')

@section('title','Terceros')

@section('route')
<li class="breadcrumb-item active" aria-current="page">Terceros</li>
@endsection

@section('content')
<hr>
<div class="row">
	<div class="col-md-12">
		<div class="row">
			<div class="col-md-4"></div>
			<div class="col-md-4">
				<buscarterceros-component></buscarterceros-component>
			</div>
		</div>
		<tablaterceros-component></tablaterceros-component>
		<modalterceros-component></modalterceros-component>
		<modaldeleteterceros-component></modaldeleteterceros-component>
	</div>
</div>
@endsection