<template>
	<div class="modal fade" id="crearTercero" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	  <div class="modal-dialog" role="document">
	    <div class="modal-content">
	      <div class="modal-header">
	        <h5 class="modal-title" id="exampleModalLabel">{{ titulo }}</h5>
	        <button type="button" @click="reset()" class="close" data-dismiss="modal" aria-label="Close">
	          <span aria-hidden="true">&times;</span>
	        </button>
	      </div>
	      <div class="modal-body">
	        <form  @submit.prevent="saveTercero"   class="form-group" id="formTerceros" autocomplete="off">
				<div class="form-group">
					<label for="nombreTercero">Nombre Completo</label>
					<input v-model="nombre_tercero" type="text" class="form-control" id="nombreTercero" aria-describedby="nombreHelp" placeholder="Nombre" onKeyPress="return soloLetrasNumeros(event)">
					<small id="nombreHelp" class="form-text text-muted">Ingrese el nombre completo del tercero.</small>
					<small id="nombreError" class="form-text text-danger">{{ error.nombre_tercero }}</small>
				</div>
				<div class="form-group">
					<label for="nit">Identificación</label>
					<input v-model="nit" type="text" class="form-control" id="nit" aria-describedby="nitHelp" placeholder="Identificación" onKeyPress="return soloNumeros(event)">
					<small id="nitHelp" class="form-text text-muted">Ingrese el número de identificación.</small>
					<small id="nitError" class="form-text text-danger">{{ error.nit }}</small>
				</div>
				<div class="form-group">
					<label for="telefono">Teléfono</label>
					<input v-model="telefono" type="text" class="form-control" id="telefono" aria-describedby="telHelp" placeholder="Teléfono" onKeyPress="return soloNumeros(event)">
					<small id="telHelp" class="form-text text-muted">Ingrese el número de teléfono.</small>
					<small id="telError" class="form-text text-danger">{{ error.telefono }}</small>
				</div>
				<div class="form-group">
					<label for="direccion">Dirección</label>
					<input v-model="direccion" type="text" class="form-control" id="direccion" aria-describedby="direccionHelp" placeholder="Dirección" onKeyPress="return soloLetrasNumeros(event)">
					<small id="direccionHelp" class="form-text text-muted">Ingrese la dirección.</small>
					<small id="direccionError" class="form-text text-danger">{{ error.direccion }}</small>
				</div>
				<div class="form-group">
					<label>Roles: </label>
					<div v-for="(unidad,key) in listaRoles"  class="form-check form-check-inline">
					  <input v-model="roles" :id="key" class="form-check-input" type="checkbox" :value="key">
					  <label class="form-check-label">{{ unidad }}</label>
					</div>
					<small id="rolHelp" class="form-text text-muted">Seleccione los roles.</small>
					<small id="rolError" class="form-text text-danger">{{ error.roles }}</small>
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
        mounted() {
            axios.get('http://beta.test/terceros').then(response => { 	this.listaRoles =  response.data.listaRoles })
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
        created(){
			EventBus.$on('tercero-error', data => {
				this.error = {}
				if(data.errors.nombre_tercero)
				{
					this.error.nombre_tercero = data.errors.nombre_tercero
				}
				if (data.errors.nit) 
				{
					this.error.nit = data.errors.nit
				}
				if (data.errors.telefono) 
				{
					this.error.telefono = data.errors.telefono
				}
				if (data.errors.direccion) 
				{
					this.error.direccion = data.errors.direccion
				}
				if (data.errors.roles) 
				{
					this.error.roles = data.errors.roles
				}
			});
		},
        methods: {
        	saveTercero: function(){
        		let metodo = this;
        		if (this.update==0)
        		{
        			axios.post('http://beta.test/terceros',{
						nombre_tercero: this.nombre_tercero,
						nit: this.nit,
						telefono: this.telefono,
						direccion: this.direccion,
						roles: this.roles
        			}).then(function(response){
	        			EventBus.$emit('tercero-added',response.data.terceros)
						metodo.reset();
        			}).catch(function(error){
        				EventBus.$emit('tercero-error',error.response.data)
        				console.log(error)
        			});
        		}
        		else
        		{
        			axios.put('http://beta.test/terceros/'+this.slug,{
	        			nombre_tercero: this.nombre_tercero,
						nit: this.nit,
						telefono: this.telefono,
						direccion: this.direccion,
						roles: this.roles,
		 				slug: this.slug
        			}).then(function(response){
        				EventBus.$emit('tercero-update',response.data.terceros)
						metodo.reset();
        			}).catch(function(error){
        				EventBus.$emit('tercero-error',error.response.data)
        				console.log(error)
        			});
        		}
        	},
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
    			$(document.body).removeClass('modal-open');
				$('.modal-backdrop').remove();
        	},

        }
    }
</script>