<template>
	<div>
		<div v-if="mostrar==false" class="modal fade" id="crearCompras" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		  <div class="modal-dialog modal-xl" role="document">
		    <div class="modal-content">
		      <div class="modal-header">
		        <h5 class="modal-title" id="exampleModalLabel">{{ titulo }}</h5>
		        <button type="button" @click="reset()" class="close" data-dismiss="modal" aria-label="Close">
		          <span aria-hidden="true">&times;</span>
		        </button>
		      </div>
		      <div class="modal-body">
		      	<form  @submit.prevent="saveProducto"   class="form-group" id="formProductos" autocomplete="off">
			      	<div class="row">
			      		<div class="col-md-3">
			      			<div class="form-group">
								<label for="nombreProducto">Fecha</label>
								<input v-model="fecha" type="date" class="form-control" id="fecha" aria-describedby="fechaHelp">
								<small id="fechaHelp" class="form-text text-muted">Seleccione la fecha de la factura.</small>
								<small id="fechaError" class="form-text text-danger">{{ error.fecha }}</small>
							</div>
			      		</div>
			      		<div class="col-md-3">
			      			<div class="form-group">
								<label for="nombreProducto">N° Factura</label>
								<input v-model="numero" type="text" class="form-control" id="numero" aria-describedby="numeroHelp" placeholder="N° Factura" onKeyPress="return soloLetrasNumeros(event)">
								<small id="numeroHelp" class="form-text text-muted">Ingrese el numero de la factura.</small>
								<small id="numeroError" class="form-text text-danger">{{ error.numero }}</small>
							</div>
			      		</div>
			      		<div class="col-md-3">
			      			<div class="form-group">
								<label for="nit">Identificación</label>
								<input v-model="nitPrevio" v-on:keydown.tab="searchTercero()" type="text" class="form-control" id="nitProveedor" aria-describedby="nitProveedorHelp" placeholder="Identificación" onKeyPress="return soloNumeros(event)">
								<small id="nitProveedorHelp" class="form-text text-muted">Ingrese el número de identificación.</small>
								<small id="nitProveedorError" class="form-text text-danger">{{ error.nitPrevio }}</small>
							</div>
			      		</div>
			      		<div class="col-md-3">
			      			<div class="form-group">
								<label for="nit">Proveedor</label>
								<input  readonly="readonly"  type="text" class="form-control" id="nombreProveedor">
							</div>
			      		</div>
			      	</div>
		      		<div v-if="cargarproductos==true"  class="row">
		      			<addproductos-component></addproductos-component>
		      		</div>
					<div class="form-group" align="center">
						<button disabled="disabled" id="botGuardar" type="submit" class="btn btn-success">Guardar</button> 
						<button type="button" @click="reset()" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
					</div>
		        </form>
		      </div>
		    </div>
		  </div>
		</div>
		<div v-if="mostrar==true"  class="row">
  			<div  class="col-md-12">
  				<modalterceros-component :nitPrevio="nitPrevio" ></modalterceros-component>
  			</div>
  		</div>
	</div>
</template>
<script>
	import EventBus from '../../event-bus';
	 export default {
	 	data() {
	 		return {
	 			fecha: "",
	 			numero: "",
	 			nitPrevio: null,
	 			mostrar: false,
	 			cargarproductos:false,
	 			slug : null,
	 			titulo : "Nueva factura de Compra",
	 			update : 0,
	 			unidades: {},
	 			error: {
	 				fecha: null,
	 				numero: null,
	 			}
	 		}
	 	},
        mounted() {
            axios.get('http://beta.test/productos').then(response => { 	this.unidades =  response.data.unidades })
            EventBus.$on('producto-edit', data => {
				this.nombre_producto = data.nombre_producto;
				this.id_unidades = data.id_unidades;
	 			this.precio_venta = data.precio_venta;
	 			this.slug = data.slug;
	 			this.update = 1;
	 			this.titulo = "Editar Producto";
			});
        },
        created(){
			EventBus.$on('producto-error', data => {
				this.error = {}
				if(data.errors.nombre_producto)
				{
					this.error.nombre_producto = data.errors.nombre_producto
				}
				if (data.errors.id_unidades) 
				{
					this.error.id_unidades = data.errors.id_unidades
				}
				if (data.errors.precio_venta) 
				{
					this.error.precio_venta = data.errors.precio_venta
				}
			});
			EventBus.$on('volver-factura', data => {
				this.mostrar = data;	
				setTimeout(function(){ $('#nitProveedor').focus(); }, 1);			
			});
		},
        methods: {
        	saveProducto: function(){
        		let metodo = this;
        		if (this.update==0)
        		{
        			axios.post('http://beta.test/productos',{
	        			nombre_producto: this.nombre_producto,
		 				id_unidades: this.id_unidades,
		 				precio_venta: this.precio_venta
        			}).then(function(response){
	        			EventBus.$emit('producto-added',response.data.productos)
						metodo.reset();
        			}).catch(function(error){
        				EventBus.$emit('producto-error',error.response.data)
        				console.log(error)
        			});
        		}
        		else
        		{
        			axios.put('http://beta.test/productos/'+this.slug,{
	        			nombre_producto: this.nombre_producto,
		 				id_unidades: this.id_unidades,
		 				precio_venta: this.precio_venta,
		 				slug: this.slug,
        			}).then(function(response){
        				EventBus.$emit('producto-update',response.data.productos)
						metodo.reset();
        			}).catch(function(error){
        				EventBus.$emit('producto-error',error.response.data)
        				console.log(error)
        			});
        		}
        	},
        	reset: function(){
        		this.nombre_producto = "" ;
				this.id_unidades = "";
	 			this.precio_venta = "";
	 			this.slug = "";
	 			this.titulo = "Nueva factura de Compra";
	 			this.update = 0;
	 			this.error = {};
	 			$('#crearProducto').modal('hide');
    			$(document.body).removeClass('modal-open');
				$('.modal-backdrop').remove();
        	},
        	searchTercero: function(e){
        		var dato = $('#nitProveedor').val()
        		let me = this;
    			axios.get('http://beta.test/terceros/'+dato
    			).then(function(response){
    				if (response.data.terceros== null) 
    				{
    					me.mostrar=true;
    					me.nitPrevio = dato;
    					setTimeout(function(){ $('#crearTercero').modal('show');  }, 1);
    					
    				}
    				else
    				{
    					me.cargarproductos=true;
    					$('#nombreProveedor').val(response.data.terceros.nombre_tercero)
    					
    				}
    			}).catch(function(error){
    				console.log(error)
    			});
        	},

        }
    }
</script>