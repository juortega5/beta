<div class="form-group">
	{!! Form::label('name','Producto',['for'=>'nombreProducto']) !!}
	{!! Form::text('nombre_producto',null,['class'=>'form-control','id'=>'nombreProducto','aria-describedby'=>'nombreHelp','placeholder'=>'Producto']) !!}
	<small id="nombreHelp" class="form-text text-muted">Ingrese el nombre del producto.</small>
</div>
<div class="form-group">
	{!! Form::label('name','Tipo de unidad',['for'=>'unidad']) !!}
	{!! Form::select('id_unidades',$select,null,['class'=>'form-control','id'=>'unidad','aria-describedby'=>'unidadHelp']) !!}
	<small id="unidadHelp" class="form-text text-muted">Indique si el producto se mide por UND o Kg.</small>
</div>
<div class="form-group">
	{!! Form::label('name','Precio',['for'=>'precio']) !!}
	{!! Form::text('precio_venta',null,['class'=>'form-control','id'=>'precio','aria-describedby'=>'precioHelp','placeholder'=>'Precio']) !!}
	<small id="precioHelp" class="form-text text-muted">Ingrese el precio de venta del producto por UND o Kg.</small>
</div>
<div class="form-group" align="center">
	{!! Form::submit($nombreBoton,['class'=>'btn btn-success']) !!}
	<a href="{{route('productos.index') }}" class="btn btn-dark">Cancelar</a>
</div>