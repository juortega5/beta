<template>
	<div align="center" class="col-md-12">
		<table id="tablaDetalle" class="table table-dark table-hover table-bordered table-sm">
			<thead>
				<tr align="center">
					<th colspan="5">Detalle de productos</th>
				</tr>
			    <tr align="center">
			      <th scope="col">Codigo</th>
			      <th scope="col">Producto</th>
			      <th scope="col">Cantidad</th>
			      <th scope="col">Precio Compra</th>
			      <th scope="col">Descartar</th>
			    </tr>
			</thead>
			<tbody>
				<tr align="center">
					<td><input class="form-control bg-dark text-white border-dark"  id="codigo0" v-on:keydown.tab="searchProductos()" type="text" name="a" ></td>
					<td><input  class="form-control bg-dark text-white border-dark" id="codigo1" type="text" name="a" ></td>
					<td><input  class="form-control bg-dark text-white border-dark" type="text" name="b"></td>
					<td><input  class="form-control bg-dark text-white border-dark" type="text" name="c" ></td>
					<td><input  class="form-control bg-dark text-white border-dark" type="text" name="d"></td>
				</tr>
			</tbody>
		</table>
	</div>
</template>
<script>
	import EventBus from '../../event-bus';
	 export default {
	 	data() {
	 		return {
	 			filas:0,
	 			
	 		}
	 	},
        mounted() {
        	$('#codigo0').focus();
        },
        created(){
			
		},
        methods: {
        	addfila: function(){

			let filaNueva = 
				'<tr>'+
					'<td><input type="text" name="a" ></td>'+
					'<td><input type="text" name="a" ></td>'+
					'<td><input type="text" name="b"></td>'+
					'<td><input type="text" name="c" v-on:keyup.enter="addfila()"></td>'+
					'<td><input type="text" name="d"></td>'+
				'</tr>';

			$('#tablaDetalle tbody').append(filaNueva);
        		
        	},
        	searchProductos: function(e){
        		var dato = $('#codigo0').val()
        		let me = this;
    			axios.get('http://beta.test/productos/'+dato
    			).then(function(response){
    				
    				$('#codigo1').val(response.data.productos.nombre_producto)
    					
    			
    			}).catch(function(error){
    				console.log(error)
    			});
        	},
        }
    }
</script>