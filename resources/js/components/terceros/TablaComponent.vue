<template>
	<section>
		<table class="table table-dark table-hover table-bordered table-sm">
			<thead>
				<tr align="center">
					<th colspan="5">Listado de Terceros</th>
					<th colspan="2">
						<button 
							class="btn btn-success btn-sm" 
							data-toggle="modal" 
							data-backdrop="static" 
							data-target="#crearTercero"
						>
							Nuevo+
						</button>
					</th>
				</tr>
			    <tr align="center">
					<th scope="col">Nombre</th>
					<th scope="col">NIT</th>
					<th scope="col">Teléfono</th>
					<th scope="col">Dirección</th>
					<th scope="col">Roles</th>
					<th scope="col">Editar</th>
					<th scope="col">Eliminar</th>
			    </tr>
			</thead>
			<tbody>
				<tr v-for="tercero in terceros">
					<td>{{ tercero.nombre_tercero }}</td>
					<td align="center">{{ $separador(tercero.nit) }}</td>      
					<td align="center">{{ tercero.telefono }}</td>
					<td align="center">{{ tercero.direccion }}</td>
					<td align="center">
						<a v-for="roles in tercero.roles">
							<span class="badge badge-success">{{ roles.rol }}</span>&#160;
						</a>
					</td>
					<td align="center">
						<button  
							@click="editTerceros(tercero)" 
							class="btn btn-primary btn-sm" 
							data-toggle="modal" 
							data-backdrop="static" 
							data-target="#crearTercero"
						>
							Editar
						</button>
					</td>
					<td align="center">
						<button  
							@click="deleteTerceros(tercero)" 
							class="btn btn-danger btn-sm" 
							data-toggle="modal" 
							data-backdrop="static" 
							data-target="#deleteTercero"
						>
						  	Eliminar
						</button>
					</td>
				</tr>
			</tbody>
		</table>
		<pagination 
			:data="paginacion" 
			@pagination-change-page="getResults"
		>
		</pagination>
	</section>	
</template>
<script>
	import EventBus from '../../event-bus';
	export default {
		data(){
			return {
				terceros: {},
				paginacion: {},
				buscar: null
			}
		},
		created(){
			//Al crear un registro actualiza la tabla.
			EventBus.$on('tercero-added', data => {
				this.terceros.push(data);
				this.getResults();
			});
			//Al buscar un registro actualiza la tabla dependiendo de la busqueda.
			EventBus.$on('tercero-search', data => {
				this.terceros = data.terceros.data;
				this.paginacion = data.terceros; 
				this.buscar = data.buscar;
			});
		},
		mounted(){
			this.getResults();
		},
		updated(){
			//Al editar o eliminar un registro actualiza la tabla.
			EventBus.$on('tercero-update', data => {
				this.getResults();
			});
		},
		beforeDestroy(){
    		EventBus.$off('tercero-added');
    		EventBus.$off('tercero-search');
    		EventBus.$off('tercero-update');
  		},
		methods: {
			//Obtiene los terceros y realiza la paginación.
			getResults(page = 1) {
				axios
					.get('http://beta.test/terceros?page=' +page+ '&buscar='+this.buscar)
					.then(response => { 
						this.terceros = response.data.terceros.data;
						this.paginacion = response.data.terceros; 
					})
				;
			},
			//Genera el evento para enviar datos a la Modal y editar.
			editTerceros(data){
				EventBus.$emit('tercero-edit',data);
			},
			//Genera el evento para enviar datos a la ModalDelete y eliminar.
			deleteTerceros(data){
				EventBus.$emit('tercero-delete',data);
			}
		},
	}
</script>