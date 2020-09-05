<template>
	<section>
		<div  
			class="modal bg-dark" 
			id="crearCompras" 
			tabindex="-1" 
			role="dialog" 
			aria-labelledby="exampleModalLabel" 
			aria-hidden="true"
		>
		  	<div 
			  class="modal-dialog modal-xl" 
			  role="document"
		  	>
			    <div class="modal-content">
					<div class="modal-header">
						<h5 
							class="modal-title" 
							id="exampleModalLabel"
						>
							{{ titulo }}
						</h5>
						<button 
							@click="reset()" 
							class="close" 
							data-dismiss="modal" 
							aria-label="Close"
						>
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
			      	<div class="modal-body">
				      	<div class="row">
				      		<div class="col-md-3">
				      			<div class="form-group">
									<label for="nitProveedor">Nombre</label>
									<input 
										v-model="nombrePrevio" 
										v-on:keyup="searchTercero()" 
										name="nitProveedor" 
										type="text" 
										class="lock form-control" 
										id="nitProveedor" 
										aria-describedby="nitProveedorHelp" 
										placeholder="Nombre"
										autocomplete="off" 
									/>
									<small 
										id="nitProveedorHelp" 
										class="form-text text-muted"
									>
										Ingrese el nombre del proveedor.
									</small>
								</div>
								<button 
									@click="validarCliente()"
									class="btn btn-success" 
									id="tercero"
								>
							 		Proveedor+
								</button>
								<button 
									class="oculto" 
									id="botonTercero"  
									data-toggle="modal" 
									data-backdrop="static" 
									data-target="#crearTercero"
								>
								</button>
								<button 
									class="oculto" 
									id="botonCliente"  
									data-toggle="modal" 
									data-backdrop="static" 
									data-target="#clienteEdit" 
								>
								</button>
								<div 
									v-for="(tercero,key) in terceros" 
									class="list-group"
								>
									<button 
										@click="cargarInformacion(tercero)" 
										class="list-group-item list-group-item-action"
									>
										{{ tercero.nombre_tercero }}
									</button>
								</div>
				      		</div>
				      		<div  
					      		v-if="mostrarComplementos==true" 
					      		class="col-md-3"
				      		>
				      			<div class="form-group">
									<label for="nit">Proveedor</label>
									<input  
										readonly="readonly"  
										type="text" 
										class="lock form-control" 
										id="nombreProveedor"
									/>
								</div>
				      		</div>
			      			<div 
			      				v-if="mostrarComplementos==true" 
			      				class="col-md-3"
			      			>
				      			<div class="form-group">
									<label for="nombreProducto">Fecha</label>
									<input 
										v-model="fecha" 
										type="date" 
										class="lock form-control" 
										id="fecha" 
										aria-describedby="fechaHelp"
										autocomplete="off" 
									/>
									<small 
										id="fechaHelp" 
										class="form-text text-muted"
									>
										Seleccione la fecha de la factura.
									</small>
									<small 
										id="fechaError" 
										class="form-text text-danger"
									>
										{{ error.fecha }}
									</small>
								</div>
				      		</div>
				      		<div 
				      			v-if="mostrarComplementos==true" 
				      			class="col-md-3"
				      		>
				      			<div class="form-group">
									<label for="nombreProducto">N° Factura</label>
									<input 
										v-model="numero" 
										v-on:keydown.tab.prevent="saveCompra()" 
										type="text" 
										class="lock form-control" 
										id="numero" 
										aria-describedby="numeroHelp" 
										placeholder="N° Factura" 
										onKeyPress="return soloLetrasNumeros(event)"
										autocomplete="off" 
									/>
									<small 
										id="numeroHelp" 
										class="form-text text-muted"
									>
										Ingrese el numero de la factura.
									</small>
									<small 
										id="numeroError" 
										class="form-text text-danger"
									>
										{{ error.numero }}
									</small>
								</div>
				      		</div>
				      	</div>
			      		<div 
				      		v-if="mostrarAgregarProductos==true"  
				      		class="row"
			      		>
				  			<div  class="col-md-12">
				  				<addproductos-component :factura="factura"></addproductos-component>
				  			</div>
				  		</div>
						<div class="form-group" align="center">
							<button 
								disabled="disabled" 
								id="botGuardar"
								@click="finalizar()" 
								class="btn btn-success"
							>
								Finalizar
							</button> 
							<button   
								@click="reset()" 
								class="btn btn-secondary" 
								data-dismiss="modal"
							>
								Cancelar
							</button>
						</div>
			      	</div>
		    	</div>
		  	</div>
		</div>
		<div 
			v-if="mostrarModalTerceros==true"  
			class="row"
		>
  			<div  class="col-md-12">
  				<modalterceros-component 
	  				:nombrePrevio="nombrePrevio" 
	  				:rol="rol"
  				>
  				</modalterceros-component>
  			</div>
  		</div>
  		<div 
  			v-if="mostrarModalClientes==true"  
  			class="row"
  		>
  			<div  class="col-md-12">
  				<cliente-component :cliente="cliente"></cliente-component>
  			</div>
  		</div>
	</section>
</template>
<script>
	import EventBus from '../../event-bus';
	 export default {
	 	data() {
	 		return {
	 			fecha: "",
	 			numero: "",
	 			tercero_id: "",
	 			nombrePrevio: null,
	 			rol:['2'],
	 			productos: [],
	 			terceros: [],
	 			cliente: [],
	 			factura:[],
	 			mostrarComplementos: false,
	 			mostrarModalTerceros: false,
	 			mostrarAgregarProductos: false,
	 			mostrarModalClientes: false,
	 			titulo : "Factura de Compra",
	 			error: {
	 				fecha: null,
	 				numero: null,
	 			}
	 		}
	 	},
	 	created(){
        	//Imprime los errores de validación.
			EventBus.$on('compra-error', data => {
				this.error = {};
				if(data.errors.fecha) {
					this.error.fecha = data.errors.fecha;
				}
				if (data.errors.numero) {
					this.error.numero = data.errors.numero;
				}
			});
        	//Evento al volver al modulo de factura desde el modal de tercero 
        	//o el modal para volver un cliente proveedor.
			EventBus.$on('volver-factura', data => {
				let me = this;
				this.mostrarModalTerceros = data[0];
				this.mostrarModalClientes = data[0];
				//Al cancelar ventana modal tercero muestra de nuevo la lista
				//de coincidencias de terceros.
				if (data[1] == 0) {
					setTimeout(function(){  me.searchTercero(); }, 1);
				}
				//Al guardar en ventana modal tercero muestra en una lista el 
				//proveedor agregado.
				else {
					setTimeout(function(){ $('#nitProveedor').focus(); me.searchTercero(); }, 1);
				}			
			});
		},
        mounted() {
           $("#tercero").hide();
           //Carga los datos de la factura para agregar o quitar productos.
           EventBus.$on('compra-edit', data => {
				let me = this;
				setTimeout(function(){  $('#nombreProveedor').val(data.tercero.nombre_tercero);  }, 1);
				me.mostrarComplementos = true;
				this.numero = data.numero; 
				this.fecha = data.fecha; 
				me.cargarProductos(data);		
			});
        },
        beforeDestroy(){
    		EventBus.$off('compra-error');
    		EventBus.$off('volver-factura');
    		EventBus.$off('compra-edit');
  		},
        methods: {
        	//Busca el tercero para cargar datos previos o crearlo.
        	searchTercero: function(){
        		var dato = $('#nitProveedor').val().trim();
        		let me = this;
        		//Evitar consultas vacías.
        		if (dato == '') {
        			me.terceros = [];
        			$("#tercero").hide();
        			me.mostrarComplementos = false;
        			return setTimeout(function(){ $('#nitProveedor').focus(); }, 1);
        		}
        		//Actualiza la modal de terceros en base al evento de onkeyup.
        		me.mostrarModalTerceros = false;
        		//Buscamos los proveedores
    			axios
	    			.get('http://beta.test/terceros/'+dato,{ params:{ rol: 'Proveedor',} })
	    			.then( response => {
	    				//Si no existe el tercero carga la modal para crearlo.
	    				//Muestro el boton para abrir la modal, oculto la lista terceros[],
	    				//oculto el resto del form de compras en caso de que haya seleccionado previamente
	    				//un tercero y lo deseo cambiar,
	    				if (response.data.terceros == 0) {
	    					$("#tercero").show();
	    					me.terceros = [];
	    					me.mostrarModalTerceros = true;
	    					me.mostrarComplementos = false;
	    				}
	    				//Carga datos del tercero en la lista terceros[], si existe.
	    				else {
	    					$("#tercero").hide();
	    					me.terceros  = response.data.terceros.data;
	    					me.mostrarModalTerceros = false;
	    				}
	    			})
	    			.catch(function(error){ console.log(error); })
	    		;
        	},
        	//Al seleccionar un elemento de la lista muestra los demas campos para diligenciar la factura.
        	cargarInformacion: function(data){
    			let me = this;
    			me.terceros = [];
    			me.mostrarComplementos = true;
				setTimeout(function(){ 
					$('#nombreProveedor').val(data.nombre_tercero); 
					me.tercero_id = data.id; 
					$('#fecha').focus();
				}, 1);
        	},
        	//Permite crear proveedores, además de cargar el rol de proveedor a cliente.
        	validarCliente: function(data){
        		var dato = $('#nitProveedor').val();
        		let me = this;
        		//Buscamos clientes
    			axios
	    			.get('http://beta.test/terceros/'+dato+'/edit')
	    			.then( response => {
	    				//Si no existe el tercero carga la modal para crearlo.
	    				if (response.data.terceros == null) {
	    					//Ejecuta el click en el boton para poder usar la propiedad static
		    				setTimeout(function(){ $('#botonTercero').click();  }, 1);
	    				}
	    				//Carga datos del tercero si existe como cliente
	    				else {
	    					//Habilitamos la modal para agregar el rol proveedor.
	    					me.mostrarModalClientes = true;
	    					me.cliente = response.data.terceros;
	    					setTimeout(function(){ $('#botonCliente').click();  }, 1);
	    				}
	    			})
	    			.catch( error => { console.log(error); })
    			;
        	},
        	//Envia los datos al controlador para guardar o editar un registro.
        	saveCompra: function(){
        		let metodo = this;
        		//Guardar registro.
        			axios
	        			.post('http://beta.test/compras',{
		        			numero: this.numero,
			 				fecha: this.fecha,
			 				tercero_id: this.tercero_id,
			 				productos: this.productos,
	        			})
	        			.then( response => {
	        				metodo.cargarProductos(response.data.compras);
	        				//Evento para hacer la actualizacion de registros al crear registros.
		        			EventBus.$emit('compra-added',response.data.compras);
	        			})
	        			.catch( error => {
	        				//Evento para enviar los errores al intentar crear registros.
	        				EventBus.$emit('compra-error',error.response.data);
	        			})
        			;
        	},
        	//Mustra el modulo de addProductos bloqueando los imputs de la factura
        	cargarProductos: function(data){
        		//Envia el id de la compra creada para añadir articulos
        		let me = this;
        		me.error = {};
        		me.factura = data;
        		setTimeout(function(){ $(".lock").prop('disabled', true);  }, 1);
        		me.mostrarAgregarProductos = true;
        	},
        	//Cierra una factura para no agregar mas productos.
        	finalizar: function(){
        		let me = this;
        		axios
        			.put('http://beta.test/compras/'+me.factura.id)
        			.then( response => {
        				me.reset();
        				//Evento para hacer la actualizacion de registros.
	        			EventBus.$emit('compra-destroy',response.data.compras);
        			})
        			.catch( error => { console.log(error); })
    			;
        	},
        	//Limpia la modal.
        	reset: function(){
        		this.fecha = "" ;
				this.numero = "";
	 			this.tercero_id = "";
	 			this.nombrePrevio = null;
	 			this.productos = [];
	 			this.terceros = [];
	 			this.error = {};
	 			this.mostrarComplementos = false;
	 			this.mostrarModalTerceros = false;
	 			this.mostrarAgregarProductos = false;
	 			$("#tercero").hide();
	 			$("#botGuardar").prop('disabled', true);
	 			$(".lock").prop('disabled', false);
	 			$('#nombreProveedor').val('');
	 			$('#crearCompras').modal('hide');
	 			$('body').removeClass('modal-open');
				$('.modal-backdrop').remove();
        	},
        }
    }
</script>