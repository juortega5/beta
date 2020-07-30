<template>
	<div>
		<table class="table table-dark table-hover table-bordered table-sm">
			<thead>
				<tr align="center">
					<th colspan="4">Facturas de compra</th>
					<th>
						<button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-backdrop="static" data-target="#crearCompras">
						  Nuevo+
						</button>
					</th>
				</tr>
			    <tr align="center">
			      <th scope="col">N° Factura</th>
			      <th scope="col">Fecha</th>
			      <th scope="col">Proveedor</th>
			      <th scope="col">Editar</th>
			      <th scope="col">Eliminar</th>
			    </tr>
			</thead>
				<tbody>
					<tr v-for="compra in compras">
						<td>{{ compra.numero }}</td>
						<td align="center">{{ compra.fecha }}</td>      
						<td align="center">{{ compra.tercero.nit }}</td>
						<td align="center">
							<button type="button" @click="editProductos(compra)" class="btn btn-primary btn-sm" data-toggle="modal" data-backdrop="static" data-target="#crearProducto">
							  Editar
							</button>
						</td>
						<td align="center">
							<button type="button" @click="deleteProductos(compra)" class="btn btn-danger btn-sm" data-toggle="modal" data-backdrop="static" data-target="#deleteCompra">
							  Eliminar
							</button>
						</td>
					</tr>
				</tbody>
		</table>
		<pagination :data="paginacion" @pagination-change-page="getResults"></pagination>
	</div>	
</template>
<style scoped>
	.pagination { justify-content: center!important; } 
</style>
<script>
	import EventBus from '../../event-bus';
	export default {
		data(){
			return {
				compras: {},
				paginacion: {},
				buscar: null
			}
		},
		mounted(){
			this.getResults();
		},
		created(){
			//Al crear un registro actualiza la tabla.
			EventBus.$on('compra-added', data => {
				this.compras.push(data)
				this.getResults()
			});
			//Al eliminar un registro actualiza la tabla.
			EventBus.$on('compra-destroy', data => {
				this.getResults()
			});
			//Al buscar un registro actualiza la tabla dependiendo de la busqueda.
			EventBus.$on('compra-search', data => {
				this.compras = data.compras.data
				this.paginacion = data.compras 
				this.buscar = data.buscar;
			});
		},
		updated(){
			//Al editar un registro actualiza la tabla.
			EventBus.$on('compra-update', data => {
				this.getResults()
			});
		},
		methods: {
			//Obtiene los productos y realiza la paginación.
			getResults(page = 1) {
					axios.get('http://beta.test/compras?page=' +page+ '&buscar='+this.buscar).then(response => { 
					this.compras = response.data.compras.data
					this.paginacion = response.data.compras; 
				});
			},
			//Genera el evento para enviar datos a la Modal y editar.
			editProductos(data){
				EventBus.$emit('compra-edit',data)
			},
			//Genera el evento para enviar datos a la ModalDelete y eliminar.
			deleteProductos(data){
				EventBus.$emit('compra-delete',data)
			}
		},
	}
</script>