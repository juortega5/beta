<template>
	<div class="modal fade" id="crearProducto" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	  <div class="modal-dialog" role="document">
	    <div class="modal-content">
	      <div class="modal-header">
	        <h5 class="modal-title" id="exampleModalLabel">{{ titulo }}</h5>
	        <button type="button" @click="reset()" class="close" data-dismiss="modal" aria-label="Close">
	          <span aria-hidden="true">&times;</span>
	        </button>
	      </div>
	      <div class="modal-body">
	        <form  @submit.prevent="saveProducto"   class="form-group" id="formProductos" autocomplete="off">
				<div class="form-group">
					<label for="codigo">Codigo</label>
					<input v-model="codigo" type="text" class="form-control" id="codigo" aria-describedby="codigoHelp">
					<small id="codigoHelp" class="form-text text-muted">Ingrese el codigo de barras.</small>
					<small id="codigoError" class="form-text text-danger">{{ error.codigo }}</small>
				</div>
				<div class="form-group">
					<label for="nombreProducto">Producto</label>
					<input v-model="nombre_producto" type="text" class="form-control" id="nombreProducto" aria-describedby="nombreHelp" placeholder="Producto" onKeyPress="return soloLetrasNumeros(event)">
					<small id="nombreHelp" class="form-text text-muted">Ingrese el nombre del producto.</small>
					<small id="nombreError" class="form-text text-danger">{{ error.nombre_producto }}</small>
				</div>
				<div class="form-group">
					<label for="unidad">Tipo de unidad</label>
					<select v-model="unidad_id" class="form-control" id="unidad" aria-describedby="unidadHelp">
						<option disabled value="">Seleccione un producto</option>
						<option v-for="(unidad,key) in unidades" :value="key">{{ unidad }}</option>
					</select>
					<small id="unidadHelp" class="form-text text-muted">Indique si el producto se mide por UND o Kg.</small>
					<small id="unidadError" class="form-text text-danger">{{ error.unidad_id }}</small>
				</div>
				<div class="form-group">
					<label for="precio">Precio</label>
					<input v-model="precio_venta" type="text" class="form-control" id="precio" aria-describedby="precioHelp" placeholder="Precio" onKeyPress="return soloNumeros(event)">
					<small id="precioHelp" class="form-text text-muted">Ingrese el precio de venta del producto por UND o Kg.</small>
					<small id="precioError" class="form-text text-danger">{{ error.precio_venta }}</small>
				</div>
				<div class="form-group" align="center">
					<button type="submit" class="btn btn-success">Guardar</button> 
					<button type="button" @click="reset()" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
				</div>
	        </form>
	      </div>
	    </div>
	  </div>
	</div>
</template>
<script>
	import EventBus from '../../event-bus';
	export default {
		props: {
			codigoPrevio: String,
		},
	 	data() {
	 		return {
	 			nombre_producto: null,
	 			unidad_id: "",
	 			precio_venta: null,
	 			slug : null,
	 			codigo: null,
	 			titulo : "Nuevo Producto",
	 			update : 0,
	 			unidades: {},
	 			error: {
	 				nombre_producto: null,
	 				unidad_id: null,
	 				precio_venta: null,
	 				codigo: null
	 			}
	 		}
	 	},
        mounted() {
        	this.codigo = this.codigoPrevio;
        	//Hace el focus al abrir la modal
        	$(function(){ $('#crearProducto').on('shown.bs.modal', function (){ $('#codigo').focus(); }); });  
        	//Carga los datos del select de tipo de unidad en la modal.
        	axios.get('http://beta.test/productos').then(response => { 	this.unidades =  response.data.unidades })
            //Carga los datos del producto en la modal cuando se edita.
            EventBus.$on('producto-edit', data => {
				this.nombre_producto = data.nombre_producto;
				this.unidad_id = data.unidad_id;
	 			this.precio_venta = data.precio_venta;
	 			this.slug = data.slug;
	 			this.codigo = data.codigo;
	 			this.update = 1;
	 			this.titulo = "Editar Producto";
			});
        },
        created(){
        	//Imprime los errores de validación.
			EventBus.$on('producto-error', data => {
				this.error = {}
				if(data.errors.nombre_producto)
				{
					this.error.nombre_producto = data.errors.nombre_producto
				}
				if (data.errors.unidad_id) 
				{
					this.error.unidad_id = data.errors.unidad_id
				}
				if (data.errors.precio_venta) 
				{
					this.error.precio_venta = data.errors.precio_venta
				}
				if (data.errors.codigo) 
				{
					this.error.codigo = data.errors.codigo
				}
			});
		},
        methods: {
        	//Envia los datos al controlador para guardar o editar un registro.
        	saveProducto: function(){
        		let metodo = this;
        		//Guardar registro.
        		if (this.update == 0)
        		{
        			axios.post('http://beta.test/productos',{
	        			nombre_producto: this.nombre_producto,
		 				unidad_id: this.unidad_id,
		 				precio_venta: this.precio_venta,
		 				codigo: this.codigo,
        			}).then(function(response){
        				//Si recibe el codigoPrevio vuelve al modulo de la factura
    					//de lo contrario actua como si fuera el modulo de productos.
	        			if (metodo.codigoPrevio == undefined) 
	        			{
	        				//Evento para hacer la actualizacion de registros al crear registros.
							EventBus.$emit('producto-added',response.data.productos)
		    			}
		    			else
		    			{
		    				//Evento para volver al modulo factura.
		    				EventBus.$emit('volver-moduloAdd',[false,1])
		    			}
						metodo.reset();
        			}).catch(function(error){
        				//Evento para enviar los errores al intentar crear registros.
        				EventBus.$emit('producto-error',error.response.data)
        				console.log(error)
        			});
        		}
        		//Editar registro.
        		else
        		{
        			axios.put('http://beta.test/productos/'+this.slug,{
	        			nombre_producto: this.nombre_producto,
		 				unidad_id: this.unidad_id,
		 				precio_venta: this.precio_venta,
		 				codigo: this.codigo,
		 				slug: this.slug,
        			}).then(function(response){
        				//Evento para hacer la actualizacion de registros al editar registros.
        				EventBus.$emit('producto-update',response.data.productos)
						metodo.reset();
        			}).catch(function(error){
        				//Evento para enviar los errores al intentar editar registros.
        				EventBus.$emit('producto-error',error.response.data)
        				console.log(error)
        			});
        		}
        	},
        	//Limpia la modal.
        	reset: function(){
        		this.nombre_producto = "" ;
				this.unidad_id = "";
	 			this.precio_venta = "";
	 			this.codigo = "";
	 			this.slug = "";
	 			this.titulo = "Nuevo Producto";
	 			this.update = 0;
	 			this.error = {};
	 			$('#crearProducto').modal('hide');
    			$('body').removeClass('modal-open');
    			//$(document.body).removeClass('modal-open');
    			//Si recibe el codigoPrevio vuelve al modulo de la factura
    			//de lo contrario actua como si fuera el modulo de productos.
				if (this.codigoPrevio == undefined) {
					$('.modal-backdrop').remove();
    			}
    			else
    			{
    				EventBus.$emit('volver-moduloAdd',[false,0])
    			}
        	},
        	
        },
       
    }
</script>