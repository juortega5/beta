<template>
	<div 
		align="center" 
		class="col-md-12"
	>
		<table class="table table-dark table-hover table-bordered table-sm">
			<thead>
				<tr align="center">
					<th colspan="7">Detalle de productos</th>
				</tr>
			    <tr align="center">
					<th scope="col">Codigo</th>
					<th scope="col">Producto</th>
					<th scope="col">Cantidad</th>
					<th scope="col">Precio Compra</th>
					<th scope="col">Precio venta</th>
					<th scope="col">Total factura</th>
					<th 
						scope="col" 
						v-if="ocultar==false"
					>
						Descartar
					</th>
			    </tr>
			</thead>
			<tbody>
				<tr 
					v-if="ocultar==false" 
					align="center"
				>
					<td>
						<input 
							class="limpiar form-control bg-dark text-white border-dark" 
							id="codigoBuscar" 
							v-on:keydown.tab="searchProductos()" 
							type="text"
							autocomplete="off" 
						/>
					</td>
					<td>
						<input 
							class="limpiar centrado form-control bg-dark text-white border-dark" 
							id="nombre"   
							readonly="readonly"  
							type="text"
						/>
					</td>
					<td>
						<input 
							class="limpiar centrado form-control bg-dark text-white border-dark" 
							id="cantidad" 
							v-on:keyup=" $escribir($event) " 
							onKeyPress="return soloNumeros(event)" 
							type="text"
							autocomplete="off" 
						/>
					</td>
					<td>
						<input 
							class="limpiar centrado form-control bg-dark text-white border-dark" 
							id="precio" 
							v-on:keyup=" $escribir($event) "   
							onKeyPress="return soloNumeros(event)" 
							v-on:keydown.tab="addprecio()" 
							autocomplete="off" 
							type="text"
						/>
					</td>
					<td>
						{{ precio_venta }}
					</td>
					<td>
						0
					</td>
					<td>
						<button 
							@click="limpiar()" 
							class="btn btn-danger btn-sm" 
						>
							Descartar
						</button>
					</td>
				</tr>
				<tr v-for="(producto,key) in productos">
					<td>{{ producto.producto.codigo }}</td>
					<td align="center">{{ producto.producto.nombre_producto }}</td>      
					<td align="center">{{ $separador(producto.cantidad) }}</td>
					<td align="center">{{ $separador(producto.precio_compra) }}</td>
					<td align="center">{{ $separador(producto.precio_venta) }}</td>
					<td align="center">
						<span class="badge badge-success">
							{{ $separador(producto.cantidad*producto.precio_compra) }}
						</span>
					</td>
					<td 
						align="center" 
						v-if="ocultar==false"
					>
						<button  
							@click="deletefila(producto.id)" 
							class="btn btn-danger btn-sm"
						>
							Descartar
						</button>
					</td>
				</tr>
				<tr align="right">
					<td colspan="5">Total factura:</td>
					<td align="center"> {{ $separador(total) }}</td>
				</tr>
			</tbody>
		</table>
		<a 
			class="oculto" 
			id="botonModalProducto" 
			data-toggle="modal" 
			data-backdrop="static" 
			data-target="#crearProducto"
		>
		</a>
		<div class="row">
  			<div 
  				v-if="mostrarModalProductos==true" 
  				class="col-md-12"
  			>
  				<modalproducto-component :codigoPrevio="codigoPrevio" ></modalproducto-component>
  			</div>
  			<div 
  				v-if="mostrarModalPrecios==true" 
  				class="col-md-12"
  			>
  				<precio-component :precioCompra="precioCompra"></precio-component>
  			</div>
  			<button 
				class="oculto" 
				id="botonPrecio"  
				data-toggle="modal" 
				data-backdrop="static" 
				data-target="#agregarPrecio"
			>
			</button>
  		</div>
	</div>
</template>
<script>
	import EventBus from '../../event-bus';
	 export default {
	 	props: {
			factura: Object,
		},
	 	data() {
	 		return {
	 			ocultar: false,
	 			codigoPrevio: null,
	 			precioCompra: null,
	 			flag: null,
	 			mostrarModalProductos: false,
	 			mostrarModalPrecios: false,
	 			idcompra:null,
	 			idProducto: '',
	 			productos:[],
	 			precio_venta: 0,
	 			total:0,
	 		}
	 	},
	 	created(){
        	//Evento al volver al modulo de factura desde el modal de productos.
			EventBus.$on('volver-moduloAdd', data => {
				let me = this;
				this.mostrarModalProductos = data[0];
				//Al cancelar ventana modal producto.
				if (data[1] == 0) {
					setTimeout(function(){ $('#codigoBuscar').focus(); }, 1);
				}
				//Al guardar en ventana modal producto.
				else {
					$('#codigoBuscar').val(data[2].codigo);
					setTimeout(function(){ $('#codigoBuscar').focus(); me.searchProductos();}, 1);
				}			
			});
			//Evento para habilitar el boton de finalizar compra y actualizar la lista de productos de la compra.
			EventBus.$on('articulo-refresh', data => {
				this.total = 0;
				let me = this;
				me.productos = data;
				//Calculo del total.
				me.productos.forEach( producto => {
						this.total = this.total + (producto.cantidad*producto.precio_compra);
					})
				//Si hay minimo un producto cargado se habilita el botón de guardar.
				if(this.productos.length > 0 && this.flag > 0) {
					$("#botGuardar").prop('disabled', false);
				}
				else {
					$("#botGuardar").prop('disabled', true);
				}	
			});
			//Evento al volver al modulo de productos desde el modal de precio.
			EventBus.$on('volver-precio', data => {
				let me = this;
				this.mostrarModalPrecios = data[0];
				//Al cancelar ventana modal precio.
				if (data[1] == 'nope') {
					setTimeout(function(){ $('#precio').focus(); }, 1);
				}
				//Al guardar en ventana modal producto.
				else {
					this.precio_venta = data[1];
					me.addfila();
				}			
			});

		},
        mounted() {
        	this.idcompra = this.factura.id;	
        	this.flag = this.factura.flag;
        	if( this.flag == 0) {
				this.ocultar = true;
			}
        	axios
	        	.get('http://beta.test/detalleCompra/'+this.idcompra)
	        	.then( response => { EventBus.$emit('articulo-refresh',response.data.articulos); })
				.catch( error => { console.log(error); })
			;
        	//Bloquear elementos al cargar el componente.
        	setTimeout(function(){ $('#codigoBuscar').focus(); }, 1);
    		$("#cantidad").prop('disabled', true);
    		$("#precio").prop('disabled', true);
        },
        beforeDestroy(){
    		EventBus.$off('volver-moduloAdd');
    		EventBus.$off('articulo-refresh');
    		EventBus.$off('volver-precio');
  		},
        methods: {
        	//Busca el pproducto para cargar datos previos o crearlo.
        	searchProductos: function(){
        		var dato = $('#codigoBuscar').val().trim();
        		let me = this;
        		//Evita que salga el input de busqueda vacío.
        		if (dato == '') {
        			setTimeout(function(){ $('#codigoBuscar').focus(); }, 1);
        			me.mostrarModalProductos = false;
        		}
        		else {
        			axios
	        			.get('http://beta.test/productos/'+dato)
	        			.then( response => {
		    				//Si no existe el producto carga la modal para crearlo.
		    				if ( response.data.productos == null) {
		    					me.mostrarModalProductos = true;
		    					me.codigoPrevio = dato;
		    					//Ejecuta el click en el boton para poder usar la propiedad static
		    					setTimeout(function(){ $('#botonModalProducto').click();  }, 1);					 
		    				}
		    				//Carga datos del producto si existe.
		    				else {
		    					$('#nombre').val(response.data.productos.nombre_producto);
		    					me.idProducto = response.data.productos.id;	
		    					$("#codigoBuscar").prop('disabled', true);
		    					$("#cantidad").prop('disabled', false);
	        					$("#precio").prop('disabled', false);
		    					setTimeout(function(){ $('#cantidad').focus(); }, 1);
		    				}
		    			})
						.catch(error => { console.log(error); })
	    			;
        		}
        	},
        	//Añade precio de venta al producto.
        	addprecio: function(){
        		var dato = $('#precio').val().trim();
        		let me = this;
        		//Evita que salga el input de busqueda vacío.
        		if (dato == '') {
        			return setTimeout(function(){ $('#precio').focus(); }, 1);
        		}
        		me.precioCompra = dato;
        		me.mostrarModalPrecios = true;
        		//Ejecuta el click en el boton para poder usar la propiedad static
		    	setTimeout(function(){ $('#botonPrecio').click();  }, 1);
        	},
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
        			axios
	        			.post('http://beta.test/detalleCompra',{
	        				producto_id: this.idProducto,
	        				cantidad: $('#cantidad').val().replace(/\./g,""),
	        				precio: $('#precio').val().replace(/\./g,""),
	        				compra_id:this.idcompra,
	        				precio_venta: this.precio_venta,
		        		})
		        		.then( response => { EventBus.$emit('articulo-refresh',response.data.articulos); })
		        		.catch(error => { console.log(error); })
	        		;
	        		//Limpia la sección donde se ingresan los productos.
					me.limpiar();		
        		}
        	},
        	//Elimina una fila de la tabla de productos.
        	deletefila: function(dato){
        		let me = this;
        		axios
	        		.delete('http://beta.test/detalleCompra/'+dato)
	        		.then( response => { EventBus.$emit('articulo-refresh',response.data.articulos); })
			        .catch(error => { console.log(error); })
    			;
        	},
        	//Limpia y habilita las opciones para cargar un producto.
        	limpiar: function(){
        		$('.limpiar').val('');
        		this.precio_venta = 0;
        		$("#codigoBuscar").prop('disabled', false);
        		$("#cantidad").prop('disabled', true);
        		$("#precio").prop('disabled', true);
        		setTimeout(function(){ $('#codigoBuscar').focus(); }, 1);
        	},
        }
    }
</script>