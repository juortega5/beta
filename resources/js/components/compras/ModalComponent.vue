<template>
	<div>
		<div  class="modal " id="crearCompras" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		  <div class="modal-dialog modal-xl" role="document">
		    <div class="modal-content">
		      <div class="modal-header">
		        <h5 class="modal-title" id="exampleModalLabel">{{ titulo }}</h5>
		        <button type="button" @click="reset()" class="close" data-dismiss="modal" aria-label="Close">
		          <span aria-hidden="true">&times;</span>
		        </button>
		      </div>
		      <div class="modal-body">
		      	<form  @submit.prevent="saveCompra"   class="form-group" id="formProductos" autocomplete="off">
			      	<div class="row">
			      		<div class="col-md-3">
			      			<div class="form-group">
			      				<button style="display: none;" id="botonTercero" type="button"  data-toggle="modal" data-backdrop="static" data-target="#crearTercero">
						 			Nuevo+
								</button>
								<label for="nit">Identificación</label>
								<input v-model="nitPrevio" v-on:keydown.tab="searchTercero()" type="text" class="form-control" id="nitProveedor" aria-describedby="nitProveedorHelp" placeholder="Identificación" onKeyPress="return soloNumeros(event)">
								<small id="nitProveedorHelp" class="form-text text-muted">Ingrese el número de identificación.</small>
								<small id="nitProveedorError" class="form-text text-danger">{{ error.nitPrevio }}</small>
							</div>
			      		</div>
			      		<div  v-if="mostrarComplementos==true" class="col-md-3">
			      			<div class="form-group">
								<label for="nit">Proveedor</label>
								<input  readonly="readonly"  type="text" class="form-control" id="nombreProveedor">
							</div>
			      		</div>
		      			<div v-if="mostrarComplementos==true" class="col-md-3">
			      			<div class="form-group">
								<label for="nombreProducto">Fecha</label>
								<input v-model="fecha" type="date" class="form-control" id="fecha" aria-describedby="fechaHelp">
								<small id="fechaHelp" class="form-text text-muted">Seleccione la fecha de la factura.</small>
								<small id="fechaError" class="form-text text-danger">{{ error.fecha }}</small>
							</div>
			      		</div>
			      		<div v-if="mostrarComplementos==true" class="col-md-3">
			      			<div class="form-group">
								<label for="nombreProducto">N° Factura</label>
								<input v-model="numero" v-on:keydown.tab="cargarProductos()" type="text" class="form-control" id="numero" aria-describedby="numeroHelp" placeholder="N° Factura" onKeyPress="return soloLetrasNumeros(event)">
								<small id="numeroHelp" class="form-text text-muted">Ingrese el numero de la factura.</small>
								<small id="numeroError" class="form-text text-danger">{{ error.numero }}</small>
							</div>
			      		</div>
			      	</div>
		      		<div v-if="mostrarAgregarProductos==true"  class="row">
			  			<div  class="col-md-12">
			  				<addproductos-component></addproductos-component>
			  			</div>
			  		</div>
					<div class="form-group" align="center">
						<button disabled="disabled" id="botGuardar" type="submit" class="btn btn-success">Guardar</button> 
						<button type="button"  @click="reset()" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
					</div>
		        </form>
		      </div>
		    </div>
		  </div>
		</div>
		<div v-if="mostrarModalTerceros==true"  class="row">
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
	 			tercero_id: "",
	 			nitPrevio: null,
	 			productos: [],
	 			mostrarComplementos: false,
	 			mostrarModalTerceros: false,
	 			mostrarAgregarProductos: false,
	 			titulo : "Nueva factura de Compra",
	 			error: {
	 				fecha: null,
	 				numero: null,
	 			}
	 		}
	 	},
        mounted() {
            
        },
        created(){
        	//Imprime los errores de validación.
			EventBus.$on('compra-error', data => {
				this.error = {}
				if(data.errors.fecha)
				{
					this.error.fecha = data.errors.fecha
				}
				if (data.errors.numero) 
				{
					this.error.numero = data.errors.numero
				}
			});
        	//Evento al volver al modulo de factura desde el modal de tercero.
			EventBus.$on('volver-factura', data => {
				let me = this;
				this.mostrarModalTerceros = data[0];
				//Al cancelar ventana modal tercero.
				if (data[1] == 0) 
				{
					setTimeout(function(){ $('#nitProveedor').focus(); }, 1);
				}
				//Al guardar en ventana modal tercero.
				else 
				{
					setTimeout(function(){ $('#nitProveedor').focus(); me.searchTercero(); }, 1);
				}			
			});
			//Agrega los productos del componente addProductos al arreglo 
			//que ira a detalle factura.
			EventBus.$on('compraDetalle-add', data => {
				this.productos = data;
			});
		},
        methods: {
        	//Busca el tercero para cargar datos previos o crearlo.
        	searchTercero: function(e){
        		var dato = $('#nitProveedor').val();
        		let me = this;
        		//Evita que salga el input de busqueda vacío.
        		if (dato == '') 
        		{
        			setTimeout(function(){ $('#nitProveedor').focus(); }, 1);
        			me.mostrarComplementos = false;
        			me.mostrarModalTerceros = false;
        			me.mostrarAgregarProductos = false;
        		}
        		else
        		{
        			axios.get('http://beta.test/terceros/'+dato
	    			).then(function(response){
	    				//Si no existe el tercero carga la modal para crearlo.
	    				if (response.data.terceros == null) 
	    				{
	    					me.mostrarModalTerceros = true;
	    					me.mostrarComplementos = false;
	    					me.mostrarAgregarProductos = false;
	    					me.nitPrevio = dato;
	    					//Ejecuta el click en el boton para poder usar la propiedad static
	    					setTimeout(function(){ $('#botonTercero').click();  }, 1);
	    				}
	    				//Carga datos del tercero si existe.
	    				else
	    				{
	    					me.mostrarComplementos = true;
	    					setTimeout(function(){ 
	    						$('#nombreProveedor').val(response.data.terceros.nombre_tercero); 
	    						me.tercero_id = response.data.terceros.id; 
	    						$('#fecha').focus();
	    					}, 1);	
	    				}
	    			}).catch(function(error){
	    				console.log(error)
	    			});
        		}
        	},
        	//Envia los datos al controlador para guardar o editar un registro.
        	saveCompra: function(){
        		let metodo = this;
        		//Guardar registro.
        			axios.post('http://beta.test/compras',{
	        			numero: this.numero,
		 				fecha: this.fecha,
		 				tercero_id: this.tercero_id,
		 				productos: this.productos,
        			}).then(function(response){
        				//Evento para hacer la actualizacion de registros al crear registros.
	        			EventBus.$emit('compra-added',response.data.compras)
	        			metodo.reset();
        			}).catch(function(error){
        				//Evento para enviar los errores al intentar crear registros.
        				EventBus.$emit('compra-error',error.response.data)
        			});
        	},
        	//Mustra el modulo de addProductos
        	cargarProductos: function(e){
        		let me = this;
        		me.mostrarAgregarProductos = true;
        	},
        	//Limpia la modal.
        	reset: function(){
        		this.fecha = "" ;
				this.numero = "";
	 			this.tercero_id = "";
	 			this.nitPrevio = null;
	 			this.productos = [];
	 			this.error = {};
	 			this.mostrarComplementos = false;
	 			this.mostrarModalTerceros = false;
	 			this.mostrarAgregarProductos = false;
	 			$("#botGuardar").prop('disabled', true);
	 			$('#nombreProveedor').val('');
	 			$('#crearCompras').modal('hide');
	 			$('body').removeClass('modal-open');
    			//$(document.body).removeClass('modal-open')
				$('.modal-backdrop').remove();
        	},
        }
    }
</script>