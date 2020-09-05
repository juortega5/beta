<template>
	<div>
		<table class="table table-dark table-hover table-bordered table-sm">
			<thead>
				<tr align="center">
					<th colspan="3">Facturas de compra</th>
					<th colspan="3">
						<button 
							type="button" 
							class="btn btn-success btn-sm" 
							data-toggle="modal" 
							data-backdrop="static" 
							data-target="#crearCompras"
						>
							Nuevo+
						</button>
					</th>
				</tr>
			    <tr align="center">
					<th scope="col">N° Factura</th>
					<th scope="col">Fecha</th>
					<th scope="col">Proveedor</th>
					<th scope="col">Acciones</th>
			    </tr>
			</thead>
				<tbody>
					<tr v-for="compra in compras">
						<td>{{ compra.numero }}</td>
						<td align="center">{{ compra.fecha }}</td>      
						<td align="center">{{ compra.tercero.nombre_tercero }}</td>
						<td align="center">
							<button 
								v-if="compra.flag==1" 
								@click="editProductos(compra)" 
								class="btn btn-primary btn-sm" 
								data-toggle="modal" 
								data-backdrop="static" 
								data-target="#crearCompras"
							>
								editar
							</button>
							<button 
								v-if="compra.flag==0" 
								@click="editProductos(compra)" 
								class="btn btn-secondary btn-sm" 
								data-toggle="modal" 
								data-backdrop="static" 
								data-target="#crearCompras"
							>
								ver
							</button>
							<button 
								v-if="compra.flag==1" 
								@click="deleteProductos(compra)" 
								class="btn btn-danger btn-sm" 
								data-toggle="modal" 
								data-backdrop="static" 
								data-target="#deleteCompra"
							>
								eliminar
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
	</div>	
</template>
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
		created(){
			//Al crear un registro actualiza la tabla.
			EventBus.$on('compra-added', data => {
				this.compras.push(data);
				this.getResults();
			});
			//Al eliminar un registro actualiza la tabla.
			EventBus.$on('compra-destroy', data => {
				this.getResults();
			});
			//Al buscar un registro actualiza la tabla dependiendo de la busqueda.
			EventBus.$on('compra-search', data => {
				this.compras = data.compras.data;
				this.paginacion = data.compras;
				this.buscar = data.buscar;
			});
		},
		mounted(){
			this.getResults();
		},
		beforeDestroy(){
    		EventBus.$off('compra-added');
    		EventBus.$off('compra-search');
    		EventBus.$off('compra-update');
  		},
		methods: {
			//Obtiene los productos y realiza la paginación.
			getResults(page = 1) {
				axios
					.get('http://beta.test/compras?page=' +page+ '&buscar='+this.buscar)
					.then(response => { 
						this.compras = response.data.compras.data;
						this.paginacion = response.data.compras; 
					})
				;
			},
			//Genera el evento para enviar datos a la Modal y editar.
			editProductos(data){
				EventBus.$emit('compra-edit',data);
			},
			//Genera el evento para enviar datos a la ModalDelete y eliminar.
			deleteProductos(data){
				EventBus.$emit('compra-delete',data);
			}
		},
	}
</script>