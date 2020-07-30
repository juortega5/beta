<template>
	<div align="center" class="col-md-12">
		<table id="tablaDetalle" class="table table-dark table-hover table-bordered table-sm">
			<thead>
				<tr align="center">
					<th colspan="5">Detalle de productos</th>
				</tr>
			    <tr align="center">
			      <th scope="col">Codigo</th>
			      <th scope="col">Producto</th>
			      <th scope="col">Cantidad</th>
			      <th scope="col">Precio Compra</th>
			      <th scope="col">Descartar</th>
			    </tr>
			</thead>
			<tbody>
				<tr align="center">
					<td><input class="limpiar form-control bg-dark text-white border-dark" id="codigoBuscar" v-on:keydown.tab="searchProductos()" type="text"></td>
					<td><input class="limpiar form-control bg-dark text-white border-dark" id="nombre"   readonly="readonly"  type="text"></td>
					<td><input class="limpiar form-control bg-dark text-white border-dark" id="cantidad" onKeyPress="return soloNumeros(event)" type="text"></td>
					<td><input class="limpiar form-control bg-dark text-white border-dark" id="precio"   onKeyPress="return soloNumeros(event)" v-on:keydown.tab="addfila()" type="text"></td>
					<td>
						<button type="button" @click="limpiar()" class="btn btn-danger btn-sm" >
							Descartar
						</button>
					</td>
				</tr>
				<tr v-for="(producto,key) in productos">
					<td>{{ producto.codigo }}</td>
					<td align="center">{{ producto.nombre }}</td>      
					<td align="center">{{ producto.cantidad }}</td>
					<td align="center">{{ producto.precio }}</td>
					<td align="center">
						<button type="button" @click="deletefila(key)" class="btn btn-danger btn-sm" >
							Descartar
						</button>
					</td>
				</tr>
			</tbody>
		</table>
		<button style="display: none;" id="botonModalProducto" type="button"  data-toggle="modal" data-backdrop="static" data-target="#crearProducto">
			Nuevo+
		</button>
		<div class="row">
  			<div v-if="mostrarModalProductos==true" class="col-md-12">
  				<modalproducto-component :codigoPrevio="codigoPrevio" ></modalproducto-component>
  			</div>
  		</div>
	</div>
</template>
<script>
	import EventBus from '../../event-bus';
	 export default {
	 	data() {
	 		return {
	 			ocultar: false,
	 			codigoPrevio: null,
	 			mostrarModalProductos: false,
	 			idProducto: '',
	 			productos:[],
	 		}
	 	},
        mounted() {
        	//Bloquear elementos al cargar el componente.
    		$("#cantidad").prop('disabled', true);
    		$("#precio").prop('disabled', true);
        },
        created(){
        	//Evento al volver al modulo de factura desde el modal de productos.
			EventBus.$on('volver-moduloAdd', data => {
				let me = this;
				this.mostrarModalProductos = data[0];
				//Al cancelar ventana modal producto.
				if (data[1] == 0) 
				{
					setTimeout(function(){ $('#codigoBuscar').focus(); }, 1);
				}
				//Al guardar en ventana modal producto.
				else 
				{
					setTimeout(function(){ $('#codigoBuscar').focus(); me.searchProductos();}, 1);
				}			
			});
		},
        methods: {
        	//Agrega una fila nueva a la tabla de productos.
        	addfila: function(){
        		let me = this;
        		//Verifica si cantidad no viene vacía.
        		if ($('#cantidad').val() == '' || $('#cantidad').val() == 0 ) 
        		{
        			setTimeout(function(){ $('#cantidad').focus(); }, 1);
        		}
        		//Verifica si precio no viene vacío.
        		else if ($('#precio').val() == '' || $('#precio').val() == 0 ) 
        		{
        			setTimeout(function(){ $('#precio').focus(); }, 1);
        		}
        		else
        		{
        			//Añade elementos al arrreglo de productos.
        			this.productos.push({
	        			codigo: $('#codigoBuscar').val(), 
	        			id: this.idProducto,
	        			nombre: $('#nombre').val(), 
	        			cantidad: $('#cantidad').val(),
	        			precio: $('#precio').val()
	        		});
	        		//Evento para ir cargando los productos que iran a detalle factura.
	        		EventBus.$emit('compraDetalle-add',this.productos);
	        		//Limpia la sección donde se ingresan los productos.
					me.limpiar();	
					//Si hay minimo un producto cargado se habilita el botón de guardar.
					if(this.productos.length > 0)
					{
						$("#botGuardar").prop('disabled', false);
					}	
        		}
        		
        	},
        	//Elimina una fila de la tabla de productos.
        	deletefila: function(dato){
        		let me = this;
        		//Elimina elementos del arreglo productos.
        		this.productos.splice(dato,1);	
        		//Evento para ir cargando los productos que iran a detalle factura.
	        	EventBus.$emit('compraDetalle-add',this.productos);
        		//Si no existen productos en el arreglo desactiva el boton guardar.
        		if(this.productos.length == 0)
				{
					$("#botGuardar").prop('disabled', true);
				}
				//Limpia la sección donde se ingresan los productos.
				me.limpiar();
        	},
        	//Busca el pproducto para cargar datos previos o crearlo.
        	searchProductos: function(){
        		var dato = $('#codigoBuscar').val();
        		let me = this;
        		//Evita que salga el input de busqueda vacío.
        		if (dato == '') 
        		{
        			setTimeout(function(){ $('#codigoBuscar').focus(); }, 1);
        			me.mostrarModalProductos = false;
        		}
        		else
        		{
        			axios.get('http://beta.test/productos/'+dato
	    			).then(function(response){
	    				//Si no existe el producto carga la modal para crearlo.
	    				if (response.data.productos == null) 
	    				{
	    					me.mostrarModalProductos = true;
	    					me.codigoPrevio = dato;
	    					//Ejecuta el click en el boton para poder usar la propiedad static
	    					setTimeout(function(){ $('#botonModalProducto').click();  }, 1);					 
	    				}
	    				//Carga datos del producto si existe.
	    				else
	    				{
	    					$('#nombre').val(response.data.productos.nombre_producto);
	    					me.idProducto = response.data.productos.id;	
	    					$("#codigoBuscar").prop('disabled', true);
	    					$("#cantidad").prop('disabled', false);
        					$("#precio").prop('disabled', false);
	    					setTimeout(function(){ $('#cantidad').focus(); }, 1);
	    				}
	    			}).catch(function(error){
	    				console.log(error)
	    			});
        		}
        	},
        	//Limpia y habilita las opciones para cargar un producto.
        	limpiar: function(){
        		$('.limpiar').val('');
        		$("#codigoBuscar").prop('disabled', false);
        		$("#cantidad").prop('disabled', true);
        		$("#precio").prop('disabled', true);
        		setTimeout(function(){ $('#codigoBuscar').focus(); }, 1);
        	},
        }
    }
</script>