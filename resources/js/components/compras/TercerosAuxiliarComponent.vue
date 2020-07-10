<template>
	<form  @submit.prevent="saveTercero"   class="form-group"  autocomplete="off">
		<div class="row">
			<div class="col-md-12">
				<div class="form-group">
					<label ><b>Nuevo Tercero</b></label>
					<small class="form-text text-muted">
						<p align="justify">En el siguiente modulo ingrese los datos del tercero como nombre, teléfono y  dirección. </p>
					</small>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-3">
				<div class="form-group">
					<label for="nombreTercero">Nombre Completo</label>
					<input v-model="nombre_tercero" type="text" class="form-control" id="nombreTercero" aria-describedby="nombreHelp" placeholder="Nombre" onKeyPress="return soloLetrasNumeros(event)">
					<small id="nombreHelp" class="form-text text-muted">Ingrese el nombre completo del tercero.</small>
					<small id="nombreError" class="form-text text-danger">{{ error.nombre_tercero }}</small>
				</div>
			</div>
			<div class="col-md-3">
				<div class="form-group">
					<label for="telefono">Teléfono</label>
					<input v-model="telefono" type="text" class="form-control" id="telefono" aria-describedby="telHelp" placeholder="Teléfono" onKeyPress="return soloNumeros(event)">
					<small id="telHelp" class="form-text text-muted">Ingrese el número de teléfono.</small>
					<small id="telError" class="form-text text-danger">{{ error.telefono }}</small>
				</div>
			</div>
			<div class="col-md-3">
				<div class="form-group">
					<label for="direccion">Dirección</label>
					<input v-model="direccion" type="text" class="form-control" id="direccion" aria-describedby="direccionHelp" placeholder="Dirección" onKeyPress="return soloLetrasNumeros(event)">
					<small id="direccionHelp" class="form-text text-muted">Ingrese la dirección.</small>
					<small id="direccionError" class="form-text text-danger">{{ error.direccion }}</small>
				</div>
			</div>
			<div class="col-md-3">
				<small id="rolHelp" class="form-text text-muted">Seleccione los roles:</small>
				<div v-for="(unidad,key) in listaRoles"  class="form-check ">
				  <input v-model="roles" :id="key" class="form-check-input position-static" type="checkbox" :value="key">
				  <label class="form-check-label">{{ unidad }}</label>
				</div>
				<small id="rolError" class="form-text text-danger">{{ error.roles }}</small>
			</div>
		</div>
		<div class="form-group" align="center">
			<button type="submit" class="btn btn-success">Guardar</button> 
			<button  @click="volverFacturaCompra()" class="btn btn-secondary" >Cancelar</button>
		</div>
    </form>
</template>
<script>
	import EventBus from '../../event-bus';
	 export default {
		props: {
			nit: String,
		},
	 	data() {
	 		return {
	 			nombre_tercero: null,
				telefono: null,
				direccion: null,
				roles : [],
				listaRoles: {},
	 			error: {
	 				nombre_tercero: null,
					telefono: null,
					direccion: null,
					roles: null
	 			}
	 		}
	 	},
        mounted() {
        	axios.get('http://beta.test/terceros').then(response => { 	this.listaRoles =  response.data.listaRoles })
        },
        created(){
			EventBus.$on('terceroAuxiliar-error', data => {
				this.error = {}
				if(data.errors.nombre_tercero)
				{
					this.error.nombre_tercero = data.errors.nombre_tercero
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
        		axios.post('http://beta.test/terceros',{
						nombre_tercero: this.nombre_tercero,
						nit: this.nit,
						telefono: this.telefono,
						direccion: this.direccion,
						roles: this.roles
        			}).then(function(response){
	        			EventBus.$emit('volver-factura',false)
        			}).catch(function(error){
        				EventBus.$emit('terceroAuxiliar-error',error.response.data)
        				console.log(error)
        			});
        	},
        	volverFacturaCompra: function(){
        		EventBus.$emit('volver-factura',false)
        	},
        }
    }
</script>