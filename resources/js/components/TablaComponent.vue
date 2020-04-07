<template>
	<div>
	<table class="table table-dark table-hover table-bordered table-sm">
		<thead>
			<tr align="center">
				<th colspan="4">Listado de Productos</th>
				<th><a class="btn btn-success btn-sm" href="create">Nuevo+</a></th>
			</tr>
		    <tr align="center">
		      <th scope="col">Nombre del producto</th>
		      <th scope="col">Unidad de medida</th>
		      <th scope="col">Precio por unidad</th>
		      <th scope="col">Editar</th>
		      <th scope="col">Eliminar</th>
		    </tr>
		</thead>
			<tbody v-for="producto in productos">
		    <tr>
		      <td>{{ producto.nombre_producto }}</td>
		      <td align="center">{{ producto.tipo_unidad.unidad }}</td>      
		      <td align="center">{{ producto.precio_venta }}</td>
		      <td align="center"><a class="btn btn-primary btn-sm" href="productos/edit">Editar</a></td>
		      <td align="center">elimnar</td>
		    </tr>
		</tbody>
	</table>
	 <pagination :data="prueba"   @pagination-change-page="getResults"></pagination>
	 </div>	
</template>
<script>
	export default {
		 data(){
		 	return {
		 		productos: {},
		 		prueba: {}
		 	}
		 },
		 mounted(){
		 	this.getResults();
		 	//axios.get('http://beta.test/productos').then(response => (this.productos = response.data.data))
		 },
		 methods: {
        getResults(page = 1) {
            axios.get('http://beta.test/productos?page=' + page).then(response => { this.productos = response.data.data;  this.prueba = response.data; });
        }
    }
	}
</script>