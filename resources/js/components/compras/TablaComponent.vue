<template>
	<div>
		<table class="table table-dark table-hover table-bordered table-sm">
			<thead>
				<tr align="center">
					<th colspan="5">Facturas de compra</th>
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
			      <th scope="col">Detalle</th>
			      <th scope="col">Editar</th>
			      <th scope="col">Eliminar</th>
			    </tr>
			</thead>
				<tbody>
					<tr v-for="producto in productos">
						<td>{{ producto.nombre_producto }}</td>
						<td align="center">{{ producto.tipo_unidad.unidad }}</td>      
						<td align="center">{{ producto.precio_venta }}</td>
						<td align="center"></td>
						<td align="center">
							<button type="button" @click="editProductos(producto)" class="btn btn-primary btn-sm" data-toggle="modal" data-backdrop="static" data-target="#crearProducto">
							  Editar
							</button>
						</td>
						<td align="center">
							<button type="button" @click="deleteProductos(producto)" class="btn btn-danger btn-sm" data-toggle="modal" data-backdrop="static" data-target="#deleteProducto">
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
				productos: {},
				paginacion: {},
				buscar: null
			}
		},
		mounted(){
			this.getResults();
		},
		created(){
			EventBus.$on('producto-added', data => {
				this.productos.push(data)
				this.getResults()
			});
			EventBus.$on('producto-destroy', data => {
				this.getResults()
			});
			EventBus.$on('producto-search', data => {
				this.productos = data.productos.data
				this.paginacion = data.productos 
				this.buscar = data.buscar;
			});
		},
		updated(){
			EventBus.$on('producto-update', data => {
				this.getResults()
			});
		},
		methods: {
			getResults(page = 1) {
					axios.get('http://beta.test/productos?page=' +page+ '&buscar='+this.buscar).then(response => { 
					this.productos = response.data.productos.data
					this.paginacion = response.data.productos; 
				});
			},
			editProductos(data){
				EventBus.$emit('producto-edit',data)
			},
			deleteProductos(data){
				EventBus.$emit('producto-delete',data)
			}
		},
	}
</script>