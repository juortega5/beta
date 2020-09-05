<template>
	<div  
		class="modal fade bg-dark" 
		id="crearTercero" 
		tabindex="-1" 
		role="dialog" 
		aria-labelledby="exampleModalLabel" 
		aria-hidden="true"
	>
	  	<div 
	  		class="modal-dialog" 
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
		        		type="button" 
		        		@click="reset()" 
		        		class="close" 
		        		data-dismiss="modal" 
		        		aria-label="Close"
		        	>
		          		<span aria-hidden="true">&times;</span>
		        	</button>
		      	</div>
		      	<div class="modal-body">
			        <form 
			        	@submit.prevent="saveTercero" 
			        	class="form-group"
			        	autocomplete="off" 
			        >
						<div class="form-group">
							<label for="nombreTercero">Nombre Completo</label>
							<input 
								v-model="nombre_tercero" 
								type="text" 
								class="form-control" 
								id="nombreTercero" 
								aria-describedby="nombreHelp" 
								placeholder="Nombre" 
								onKeyPress="return soloLetrasNumeros(event)"
							/>
							<small 
								id="nombreHelp" 
								class="form-text text-muted"
							>
								Ingrese el nombre completo del tercero.
							</small>
							<small 
								id="nombreError" 
								class="form-text text-danger"
							>
								{{ error.nombre_tercero }}
							</small>
						</div>
						<div class="form-group">
							<label for="nit">Identificación</label>
							<input 
								v-model="nit" 
								type="text" 
								class="form-control" 
								id="nit" 
								aria-describedby="nitHelp" 
								placeholder="Identificación" 
								onKeyPress="return soloNumeros(event)"
							/>
							<small 
								id="nitHelp" 
								class="form-text text-muted"
							>
								Ingrese el número de identificación.
							</small>
							<small 
								id="nitError" 
								class="form-text text-danger"
							>
								{{ error.nit }}
							</small>
						</div>
						<div class="form-group">
							<label for="telefono">Teléfono</label>
							<input 
								v-model="telefono" 
								type="text" 
								class="form-control" 
								id="telefono" 
								aria-describedby="telHelp" 
								placeholder="Teléfono" 
								onKeyPress="return soloNumeros(event)"
							/>
							<small 
								id="telHelp" 
								class="form-text text-muted"
							>
								Ingrese el número de teléfono.
							</small>
							<small 
								id="telError" 
								class="form-text text-danger"
							>
								{{ error.telefono }}
							</small>
						</div>
						<div class="form-group">
							<label for="direccion">Dirección</label>
							<input 
								v-model="direccion" 
								type="text" 
								class="form-control" 
								id="direccion" 
								aria-describedby="direccionHelp" 
								placeholder="Dirección" 
								onKeyPress="return soloLetrasNumeros(event)"
							>
							<small 
								id="direccionHelp" 
								class="form-text text-muted"
							>
								Ingrese la dirección.
							</small>
							<small 
								id="direccionError" 
								class="form-text text-danger"
							>	
								{{ error.direccion }}
							</small>
						</div>
						<div 
							v-if="nombrePrevio == undefined" 
							class="form-group"
						>
							<label>Roles: </label>
							<div 
								v-for="(unidad,key) in listaRoles"  
								class="form-check form-check-inline"
							>
							  	<input 
							  		v-model="roles" 
							  		:id="key" 
							  		class="form-check-input" 
							  		type="checkbox" 
							  		:value="key"
							  	/>
							 	<label class="form-check-label">{{ unidad }}</label>
							</div>
							<small 
								id="rolHelp" 
								class="form-text text-muted"
							>
								Seleccione los roles.
							</small>
							<small 
								id="rolError" 
								class="form-text text-danger"
							>
								{{ error.roles }}
							</small>
						</div>
						<div 
							class="form-group" 
							align="center"
						>
							<button 
								type="submit" 
								class="btn btn-success" 
							>
								Guardar
							</button> 
							<button 
								type="button" 
								@click="reset()" 
								class="btn btn-secondary" 
								data-dismiss="modal"
							>
								Cancelar
							</button>
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
			nombrePrevio: String,
			rol: Array,
		},
	 	data() {
	 		return {
				nombre_tercero: null,
				nit: null,
				telefono: null,
				direccion: null,
	 			slug : null,
	 			roles : [],
	 			titulo : "Nuevo Tercero",
	 			update : 0,
	 			listaRoles: {},
	 			error: {
	 				nombre_tercero: null,
					nit: null,
					telefono: null,
					direccion: null,
					roles: null
	 			}
	 		}
	 	},
	 	created(){
        	//Imprime los errores de validación.
			EventBus.$on('tercero-error', data => {
				this.error = {};
				if(data.errors.nombre_tercero){
					this.error.nombre_tercero = data.errors.nombre_tercero;
				}
				if (data.errors.nit){
					this.error.nit = data.errors.nit;
				}
				if (data.errors.telefono){
					this.error.telefono = data.errors.telefono;
				}
				if (data.errors.direccion){
					this.error.direccion = data.errors.direccion;
				}
				if (data.errors.roles){
					this.error.roles = data.errors.roles;
				}
			});
		},
        mounted() {
        	//Asignaciones cuando se reciben las propiedades.
        	if (this.rol != undefined && this.nombrePrevio != undefined) {
        		this.nombre_tercero = this.nombrePrevio;
        		this.roles = this.rol;
        	}
        	//Carga los datos del select de los roles en la modal.
            axios
            	.get('http://beta.test/terceros')
            	.then( response => { this.listaRoles =  response.data.listaRoles })
            ;
            //Carga los datos del tercero en la modal cuando se edita.
            EventBus.$on('tercero-edit', data => {
				this.nombre_tercero = data.nombre_tercero;
				this.nit = data.nit;
	 			this.telefono = data.telefono;
	 			this.direccion = data.direccion;
	 			this.slug = data.slug;
				for (var i=0; i<data.roles.length; i++) {
					$('#'+data.roles[i].id).click();
				}
	 			this.update = 1;
	 			this.titulo = "Editar Tercero";
			});
        },
		beforeDestroy(){
    		EventBus.$off('tercero-edit');
    		EventBus.$off('tercero-error');
  		},
        methods: {
        	//Envia los datos al controlador para guardar o editar un registro.
        	saveTercero: function(){
        		let metodo = this;
        		//Guardar registro.
        		if (this.update == 0){
        			axios
        				.post('http://beta.test/terceros',{
							nombre_tercero: this.nombre_tercero,
							nit: this.nit,
							telefono: this.telefono,
							direccion: this.direccion,
							roles: this.roles
        				})
        				.then( response => {
	        				//Si recibe el nit previo vuelve al modulo de la factura
	    					//de lo contrario actua como si fuera el modulo de terceros.
	        				if (metodo.nombrePrevio == undefined) {
								//Evento para hacer la actualizacion de registros al crear registros.
		        				EventBus.$emit('tercero-added',response.data.terceros);
			    			}
			    			else {
			    				//Evento para volver al modulo factura.
			    				EventBus.$emit('volver-factura',[false,1]);
			    			}
							metodo.reset();
        				})
        				.catch( error => {
	        				//Evento para enviar los errores al intentar crear registros.
	        				EventBus.$emit('tercero-error',error.response.data);
	        				console.log(error);
        				})
        			;
        		}
        		//Editar registro.
        		else{
        			axios
        				.put('http://beta.test/terceros/'+this.slug,{
		        			nombre_tercero: this.nombre_tercero,
							nit: this.nit,
							telefono: this.telefono,
							direccion: this.direccion,
							roles: this.roles,
			 				slug: this.slug
        				})
        				.then( response =>{
	        				//Evento para hacer la actualizacion de registros al editar registros.
	        				EventBus.$emit('tercero-update',response.data.terceros);
							metodo.reset();
        				})
        				.catch( error => {
	        				//Evento para enviar los errores al intentar editar registros.
	        				EventBus.$emit('tercero-error',error.response.data);
	        				console.log(error);
        			});
        		}
        	},
        	//Limpia la modal.
        	reset: function(){
        		this.nombre_tercero = "" ;
				this.nit = "";
	 			this.telefono = "";
	 			this.direccion = "";
	 			this.roles = [];
	 			this.titulo = "Nuevo Tercero";
	 			this.update = 0;
	 			this.error = {};
	 			$('input').prop( "checked",false)	
	 			$('#crearTercero').modal('hide');
	 			$('body').removeClass('modal-open');
    			//Si recibe el nit previo vuelve al modulo de la factura
    			//de lo contrario actua como si fuera el modulo de terceros.
    			if (this.nombrePrevio == undefined) {
					$('.modal-backdrop').remove();
    			}
    			else {
    				EventBus.$emit('volver-factura',[false,0]);
    			}
        	},
        }
    }
</script>