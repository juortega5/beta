<template>
	<div>
		<table class="table table-dark table-hover table-bordered table-sm">
			<thead>
				<tr align="center">
					<th colspan="5">Listado de Productos</th>
					<th colspan="2">
						<button 
							class="btn btn-success btn-sm" 
							data-toggle="modal" 
							data-backdrop="static" 
							data-target="#crearProducto"
						>
						 	Nuevo+
						</button>
					</th>
				</tr>
			    <tr align="center">
			      <th scope="col">Nombre del producto</th>
			      <th scope="col">Unidad de medida</th>
			      <th scope="col">Categoría</th>
			      <th scope="col">Precio por unidad</th>
			      <th scope="col">Editar</th>
			      <th scope="col">Eliminar</th>
			    </tr>
			</thead>
				<tbody>
					<tr v-for="producto in productos">
						<td>{{ producto.nombre_producto }}</td>
						<td align="center">{{ producto.unidad.unidad }}</td>   
						<td align="center">{{ producto.categoria.categoria }}</td>    
						<td align="center">{{ $separador(producto.precio_venta) }}</td>
						<td align="center">
							<button 
								@click="editProductos(producto)" 
								class="btn btn-primary btn-sm" 
								data-toggle="modal" 
								data-backdrop="static" 
								data-target="#crearProducto"
							>
							 	Editar
							</button>
						</td>
						<td align="center">
							<button 
								@click="deleteProductos(producto)" 
								class="btn btn-danger btn-sm" 
								data-toggle="modal" 
								data-backdrop="static" 
								data-target="#deleteProducto"
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
	</div>	
</template>
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
		created(){
			//Al crear un registro actualiza la tabla.
			EventBus.$on('producto-added', data => {
				this.productos.push(data);
				this.getResults();
			});
			//Al buscar un registro actualiza la tabla dependiendo de la busqueda.
			EventBus.$on('producto-search', data => {
				this.productos = data.productos.data;
				this.paginacion = data.productos;
				this.buscar = data.buscar;
			});
		},
		mounted(){
			this.getResults();
		},
		updated(){
			//Al editar o eliminar un registro actualiza la tabla.
			EventBus.$on('producto-update', data => {
				this.getResults();
			});
		},
		beforeDestroy(){
    		EventBus.$off('producto-added');
    		EventBus.$off('producto-search');
    		EventBus.$off('producto-update');
  		},
		methods: {
			//Obtiene los productos y realiza la paginación.
			getResults(page = 1) {
				axios
					.get('http://beta.test/productos?page=' +page+ '&buscar='+this.buscar)
					.then(response => { 
						this.productos = response.data.productos.data;
						this.paginacion = response.data.productos; 
					})
				;
			},
			//Genera el evento para enviar datos a la Modal y editar.
			editProductos(data){
				EventBus.$emit('producto-edit',data);
			},
			//Genera el evento para enviar datos a la ModalDelete y eliminar.
			deleteProductos(data){
				EventBus.$emit('producto-delete',data);
			}
		},
	}
</script>