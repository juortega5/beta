<template>
	<div align="center" class="col-md-12">
		<table v-if="ocultar==false" id="tablaDetalle" class="table table-dark table-hover table-bordered table-sm">
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
					<td><input class="codigos form-control bg-dark text-white border-dark" id="0" v-on:keydown.tab="searchProductos()" type="text"></td>
					<td><input class="form-control bg-dark text-white border-dark" id="nombre0" type="text"></td>
					<td><input class="form-control bg-dark text-white border-dark" id="cantidad0" type="text"></td>
					<td><input class="form-control bg-dark text-white border-dark" id="precio0" v-on:keydown.enter="addfila()" type="text"></td>
					<td></td>
				</tr>
			</tbody>
		</table>
		<div class="row">
  			<div  class="col-md-12">
  				<modalproducto-component></modalproducto-component>
  			</div>
  		</div>
	</div>
</template>
<script>
	import EventBus from '../../event-bus';
	 export default {
	 	data() {
	 		return {
	 			ocultar:false,
	 			productos:{},
	 		}
	 	},
        mounted() {
        	$('#0').focus();
        },
        created(){
			
		},
        methods: {
        	addfila: function(){
				let filaNueva = 
				'\
				<tr align="center">\
					<td><input class="form-control bg-dark text-white border-dark" type="text"></td>\
					<td><input class="form-control bg-dark text-white border-dark" type="text"></td>\
					<td><input class="form-control bg-dark text-white border-dark" type="text"></td>\
					<td><input class="form-control bg-dark text-white border-dark" type="text"></td>\
					<td><button class="button"></button></td>\
				</tr>\
				';
				$('#tablaDetalle tbody').append(filaNueva);	
				//Hace una pausa para que el focus no salte al siguiente input.			
				setTimeout(function(){ $('#'+complementoId).focus(); }, 1);
        	},
        	searchProductos: function(){
        		var id = event.target.id;
        		var dato = event.target.value;
        		let me = this;
    			axios.get('http://beta.test/productos/'+dato
    			).then(function(response){
    				if (response.data.productos == null) 
    				{
    					$('#crearProducto').modal('show'); 
    				}
    				else
    				{
    					$('#nombre'+id).val(response.data.productos.nombre_producto)	
    				}
    			}).catch(function(error){
    				console.log(error)
    			});
        	},
        }
    }
</script>